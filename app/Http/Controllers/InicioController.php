<?php

namespace App\Http\Controllers;

use App\ASemillaPrestamo;
use App\HAuditoria;
use App\HEstadoPrestamo;
use App\HOcurrenciaPrestamo;
use App\InversionistaProyecto;
use App\Mail\NotificacionProyectoAprobado;
use App\Mail\NotificacionProyectoAprobadoCola;
use App\Mail\NotificacionProyectoAprobadoInversionista;
use App\MeInteresa;
use App\PInversionista;
use App\PNotificacion;
use App\PPersona;
use App\PPrestamo;
use App\RPrestamoInversionista;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class InicioController extends Controller
{   

    public function index(Request $request){
               
        $distritos = DB ::table('p_distrito')->select('co_distrito','no_distrito')->get();
        $ubicacion = $request->distritos;
        $monto = $request->monto;
     
        $solicitantes = DB::table('p_solicitud_prestamo')
            ->join('p_persona', 'p_persona.co_persona', 'p_solicitud_prestamo.co_persona')
            ->join('a_tiempo_pago', 'p_solicitud_prestamo.co_tiempo_pago', 'a_tiempo_pago.co_tiempo_pago')
            ->join('a_forma_pago', 'p_solicitud_prestamo.co_forma_pago', 'a_forma_pago.co_forma_pago')
            ->leftJoin('a_tipo_moneda', 'p_solicitud_prestamo.co_tipo_moneda', 'a_tipo_moneda.co_tipo_moneda')
            ->join('p_prestamo', 'p_solicitud_prestamo.co_solicitud_prestamo', 'p_prestamo.co_solicitud_prestamo')
            ->leftJoin('r_prestamo_inversionista', function($join) {
                $join->on('r_prestamo_inversionista.co_prestamo', '=', 'p_prestamo.co_prestamo')
                     ->where('r_prestamo_inversionista.in_estado', '=', 1);
            })
            ->join('a_estado', 'p_prestamo.co_estado', 'a_estado.co_estado')
            ->leftJoin('p_cartera', 'p_prestamo.co_prestamo', 'p_cartera.co_prestamo')
            ->leftJoin('p_distrito', 'p_distrito.co_distrito', 'p_solicitud_prestamo.co_distrito')
            ->leftJoin('a_tipo_credito', 'p_solicitud_prestamo.co_tipo_credito', 'a_tipo_credito.co_tipo_credito')
            ->leftJoin('a_tipo_garantia','p_solicitud_prestamo.co_tipo_garantia','a_tipo_garantia.co_tipo_garantia')
            ->leftJoin('a_tipo_cliente','p_prestamo.co_tipo_cliente','a_tipo_cliente.co_tipo_cliente')
            ->leftJoin('users_gi','p_persona.co_persona','users_gi.inversionista_id')
            ->leftJoin('me_interesa','users_gi.id','me_interesa.co_inversionista')
            ->leftJoin('datos_prestamo', 'datos_prestamo.co_solicitud_prestamo', 'p_solicitud_prestamo.co_solicitud_prestamo')
            ->leftJoin('a_tipo_moneda AS tipo_moneda_dato_prestamo', 'datos_prestamo.co_tipo_moneda', 'tipo_moneda_dato_prestamo.co_tipo_moneda')
            ->where([
                'p_solicitud_prestamo.in_estado' => 1,
                'p_prestamo.in_estado' => 1,
            ])
            ->select(
                'r_prestamo_inversionista.co_inversionista',
                'p_prestamo.co_ocurrencia_actual',
                'no_forma_pago',
                'no_tiempo_pago', 
                'p_solicitud_prestamo.nu_total_solicitado', 
                'p_solicitud_prestamo.co_solicitud_prestamo',
                'p_prestamo.fe_usuario_modifica', 
                'p_prestamo.co_estado', 
                'a_estado.no_estado',
                'p_prestamo.co_prestamo',  
                'nu_monto_prestamo',
                'a_tipo_moneda.nc_tipo_moneda',
                'co_unico_solicitud', 
                'co_unico_prestamo', 
                'fe_solicitud_prestamo',
                'no_distrito', 
                'a_tipo_credito.no_tipo_credito',
                'a_tipo_garantia.no_tipo_garantia',
                'a_tipo_garantia.co_tipo_garantia',
                'a_tipo_cliente.nu_tasa_interes_mensual',
                'me_interesa.estado',
                'tipo_moneda_dato_prestamo.nc_tipo_moneda AS tipo_moneda_dato_prestamo',
                'valor_comercial_inmueble',
                DB::raw("(select url_evidencia from r_imagenes_inmueble imagen where imagen.co_solicitud_prestamo = p_solicitud_prestamo.co_solicitud_prestamo and in_estado = 1 order by id asc limit 1) as imagen_principal")
            )
            ->whereRaw("p_prestamo.co_ocurrencia_actual IN (31, 32, 33, 34, 36)")
            ->where(function($q) {
                $q->where('p_prestamo.co_condicion_actual', '!=', 58)->orWhereNull('co_condicion_actual');
            })   
            ->whereRaw("(select url_evidencia from r_imagenes_inmueble imagen where imagen.co_solicitud_prestamo = p_solicitud_prestamo.co_solicitud_prestamo and in_estado = 1 order by id asc limit 1) IS NOT NULL")
            ->where(function($query)use($request){
                if($request->distrito){
                    $query->where('p_distrito.co_distrito',$request->distrito);
                }
            })
            ->where(function ($query) use ($request) {
                if ($request->monto) {
                    $query->where('p_solicitud_prestamo.nu_total_solicitado', '>=', $request->monto);
                }
            })
            ->where(function ($query) use ($request){
                if($request->co_tipo_garantia){
                    $query->where('a_tipo_garantia.co_tipo_garantia', $request->co_tipo_garantia);  
                }
            })
            ->orderBy('fe_solicitud_prestamo','desc')
        ->get();

        session(['distrito' => $request->input('distrito')]);
        session(['monto' => $request->input('monto')]);

        $solicitantesprocesados = $solicitantes->map(function($solicitante){
            $solicitante->interesado = null;
            $solicitante->aprobadoPorUserActual = false;

            $p_inversionista = PPersona::join('p_solicitud_inversionista AS soli', 'soli.co_persona', 'p_persona.co_persona')
                ->join('p_inversionista AS pi', 'pi.co_solicitud_inversionista', 'soli.co_solicitud_inversionista')
                ->where('soli.in_estado', 1)
                ->where('p_persona.in_estado', 1)
                ->where('p_persona.co_persona', Auth::user()->inversionista_id)
                ->select('pi.co_inversionista')
            ->first();
            if ( $p_inversionista && $solicitante->co_inversionista == $p_inversionista->co_inversionista ) {
                $solicitante->aprobadoPorUserActual = true;
            }
            
            $inversionista_proyecto = InversionistaProyecto::where([
                    'prestamo_id' => $solicitante->co_prestamo,
                    'persona_id'  => Auth::user()->inversionista_id,
                    'estado'      => 1,
                ])
            ->first('prioridad');
            if ( $inversionista_proyecto ) {
                $solicitante->aprobadoPorUserActual = true;
            }

            // MAX aprobados
            $solicitante->total_aprobados_proyecto = 0;
            $co_persona_asignada = 0;

            $proyectoAsignado = RPrestamoInversionista::where([
                    'co_prestamo' => $solicitante->co_prestamo,
                    'in_estado'   => 1,
                ])
            ->first();
            if ( $proyectoAsignado ) {
                $solicitante->total_aprobados_proyecto += 1;

                $persona = RPrestamoInversionista::join('p_inversionista AS pi', 'pi.co_inversionista', 'r_prestamo_inversionista.co_inversionista')
                    ->join('p_solicitud_inversionista AS soli', 'soli.co_solicitud_inversionista', 'pi.co_solicitud_inversionista')
                    ->join('p_persona AS p', 'p.co_persona', 'soli.co_persona')
                    ->where('r_prestamo_inversionista.co_prestamo', $solicitante->co_prestamo)
                    ->where('r_prestamo_inversionista.in_estado', 1)
                    ->where('pi.in_estado', 1)
                    ->where('soli.in_estado', 1)
                    ->where('p.in_estado', 1)
                    ->select('p.co_persona')
                ->first();
                $co_persona_asignada = $persona->co_persona;
            }
            
            $cant_aprobados_plataforma = InversionistaProyecto::where('prestamo_id', $solicitante->co_prestamo)
                ->where('persona_id', '!=', $co_persona_asignada)
            ->count();
            if ( $cant_aprobados_plataforma ) {
                $solicitante->total_aprobados_proyecto += $cant_aprobados_plataforma;
            }

            $like = MeInteresa::where([
                    'co_prestamo'=> $solicitante->co_prestamo,
                    'co_inversionista'=>Auth::user()->id
                ])
            ->first();
            if ($like) {
                $solicitante->interesado = $like;
            }
            return $solicitante;
        });

        $solicitantesprocesados = collect($solicitantesprocesados);
        $perPage = 10;
        // Utiliza el método paginate para crear un LengthAwarePaginator
        $solicitantesprocesados = new \Illuminate\Pagination\LengthAwarePaginator(
            $solicitantesprocesados->forPage(\Illuminate\Pagination\Paginator::resolveCurrentPage(), $perPage),
            $solicitantesprocesados->count(),
            $perPage,
            \Illuminate\Pagination\Paginator::resolveCurrentPage(),
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );
        
        $totalLikesPorPrestamo = MeInteresa::where('estado', 1)
            ->groupBy('co_prestamo')
            ->selectRaw('co_prestamo, count(*) as cantidad_likes')
            ->get()
        ->pluck('cantidad_likes', 'co_prestamo');

        $analista = DB::table('p_usuario')
            ->join('p_solicitud_inversionista', 'p_solicitud_inversionista.co_usuario', 'p_usuario.co_usuario')
            ->where('p_solicitud_inversionista.co_persona', Auth::user()->inversionista_id)
            ->select(
                'name as analista_nombre',
                DB::raw("IFNULL(nu_celular_trabajo, '946038148') as celular"),
                'email as analista_email'
            )
        ->first();

        $total_aprobados = InversionistaProyecto::groupBy('prestamo_id')
            ->selectRaw('prestamo_id, count(*) as cantidad_aprobados')
            ->get()
        ->pluck('cantidad_aprobados', 'prestamo_id');

        return view('giapp.inicio.inicio', compact('solicitantesprocesados', 'distritos','totalLikesPorPrestamo','ubicacion', 'monto', 'analista', 'total_aprobados'));
    }

    public function meInteresa(Request $request)
    {
        $like = MeInteresa::where([
                'co_prestamo'=> $request->co_prestamo,
                'co_inversionista'=> Auth::user()->id,
            ])
        ->first();

        if($like){
            $like->estado = $like->estado == 1 ? 0 : 1;
            $like->fe_modificacion = now();
            $like->save();
        }else{
            $like = new MeInteresa();
            $like->co_prestamo = $request->co_prestamo;
            $like->comentario = '';
            $like->estado = 1;
            $like->co_inversionista = Auth::user()->id;
            $like->fe_interaccion = now();
            $like->fe_modificacion = now();
            $like->save();
        }

        $cantidad = MeInteresa::where('co_prestamo', $request->co_prestamo)->where('estado', 1)->count();



        $response=[
            'like_actual' => $like,
            'cantidad'    => $cantidad,
        ];
        
        return response()->json($response);
    }

    public function dislike(Request $request){
        $like = MeInteresa::where([
                'co_prestamo' => $request->co_prestamo,
                'co_inversionista' => Auth::user()->id,
            ])
        ->first();
    
        if ($like != null) {
            $like->estado = 0; // Cambiar el estado a 0
            $like->fe_modificacion = now();
            $like->save();
        }
    
        $response = [
            'like_actual' => $like
        ];
    
        return response()->json($response);
    }

    public function aceptar_proyecto(Request $request)
    {
        try {
            DB::beginTransaction();

            $max_prioridad_proyecto = InversionistaProyecto::where('prestamo_id', $request->codigo_prestamo)->max('prioridad');
            $prioridad = $max_prioridad_proyecto ? $max_prioridad_proyecto + 1 : 1;

            $exist_inversionista = InversionistaProyecto::where([
                    'prestamo_id' => $request->codigo_prestamo,
                    'persona_id' => Auth::user()->inversionista_id,
                    'estado' => 1,
                ])
            ->first();
            if ( $exist_inversionista ) {
                $response = [
                    'http_code' => 400,
                    'message'   => "Usted ya aprobó este proyecto. Refrescar la página.",
                    'status'    => "Error",
                ];
                DB::rollBack();
                return response()->json($response);
            }
            InversionistaProyecto::create([
                'prestamo_id' => $request->codigo_prestamo,
                'persona_id' => Auth::user()->inversionista_id,
                'prioridad' => $prioridad,
                'estado' => 1,
            ]);

            /* $total_aprobados = InversionistaProyecto::where([
                'prestamo_id' => $request->codigo_prestamo,
                // 'estado' => 1,
                ])
            ->count(); */
            // MAX aprobados
            $total_aprobados = 0;
            $co_persona_asignada = 0;

            $proyectoAsignado = RPrestamoInversionista::where([
                    'co_prestamo' => $request->codigo_prestamo,
                    'in_estado'   => 1,
                ])
            ->first();
            if ( $proyectoAsignado ) {
                $total_aprobados += 1;

                $persona = RPrestamoInversionista::join('p_inversionista AS pi', 'pi.co_inversionista', 'r_prestamo_inversionista.co_inversionista')
                    ->join('p_solicitud_inversionista AS soli', 'soli.co_solicitud_inversionista', 'pi.co_solicitud_inversionista')
                    ->join('p_persona AS p', 'p.co_persona', 'soli.co_persona')
                    ->where('r_prestamo_inversionista.co_prestamo', $request->codigo_prestamo)
                    ->where('r_prestamo_inversionista.in_estado', 1)
                    ->where('pi.in_estado', 1)
                    ->where('soli.in_estado', 1)
                    ->where('p.in_estado', 1)
                    ->select('p.co_persona')
                ->first();
                $co_persona_asignada = $persona->co_persona;
            }
            
            $cant_aprobados_plataforma = InversionistaProyecto::where('prestamo_id', $request->codigo_prestamo)
                ->where('persona_id', '!=', $co_persona_asignada)
            ->count();
            if ( $cant_aprobados_plataforma ) {
                $total_aprobados += $cant_aprobados_plataforma;
            }
            
            $p_inversionista = PPersona::join('p_solicitud_inversionista AS soli', 'soli.co_persona', 'p_persona.co_persona')
                ->join('p_inversionista AS pi', 'pi.co_solicitud_inversionista', 'soli.co_solicitud_inversionista')
                ->where('soli.in_estado', 1)
                ->where('p_persona.in_estado', 1)
                ->where('p_persona.co_persona', Auth::user()->inversionista_id)
                ->select('pi.co_inversionista', 'p_persona.no_completo_persona', 'p_persona.no_correo_electronico')
            ->first();
                
            $prestamo = PPrestamo::join('p_solicitud_prestamo', 'p_prestamo.co_solicitud_prestamo', 'p_solicitud_prestamo.co_solicitud_prestamo')
                ->where('co_prestamo', $request->codigo_prestamo)
                ->select(
                    'p_prestamo.co_usuario', 
                    'p_prestamo.co_unico_prestamo', 
                    'p_solicitud_prestamo.co_sede', 
                    'p_solicitud_prestamo.co_producto', 
                    'p_solicitud_prestamo.co_unico_solicitud', 
                    'p_solicitud_prestamo.co_solicitud_prestamo'
                )
            ->first();
    
            $inversionista_gestor = PInversionista::join('p_solicitud_inversionista', 'p_solicitud_inversionista.co_solicitud_inversionista', 'p_inversionista.co_solicitud_inversionista')
                ->where('co_inversionista', $p_inversionista->co_inversionista)
                ->select('p_solicitud_inversionista.co_usuario as gestor')
            ->first();

            $analista = DB::table('p_usuario')
                ->where('co_usuario', $inversionista_gestor->gestor)
            ->first();
            $analistas_emails = DB::table('p_usuario')
                ->where('co_perfil', 12)
                ->pluck('email')
            ->toArray();
            $analistas_emails[] = $analista->email;

            $enviar_wsp = false;
            $parametro = DB::table('parametros')
                ->where('codigo', 'wsp-plat')
                ->where('estado', '1')
            ->first();
            if ( $parametro ) {
                $enviar_wsp = true;
            }

            if ( isset($max_prioridad_proyecto) ) {
                Mail::to($p_inversionista->no_correo_electronico)->cc($analistas_emails)->send(new NotificacionProyectoAprobadoCola($prestamo->co_unico_solicitud, $p_inversionista->no_completo_persona, $analista->name, $prestamo->co_solicitud_prestamo, $prioridad));

                $response = [
                    'http_code' => 200,
                    'message'   => "Proyecto aprobado correctamente.",
                    'detail'    => "Estas en cola para el proyecto, eres el número: $prioridad.",
                    'status'    => "Success",
                    'analista'  => $analista->nu_celular_trabajo,
                    'co_unico'  => $prestamo->co_unico_solicitud,
                    'prestamo'  => $request->codigo_prestamo,
                    'persona'   => Auth::user()->inversionista_id,
                    'total_aprobados' => $total_aprobados,
                    'enviar_wsp' => $enviar_wsp,
                ];
            } else {
                // Validar que el proyecto no este asignado
                $proyectoAsignado = RPrestamoInversionista::where([
                        'co_prestamo' => $request->codigo_prestamo,
                        'in_estado'   => 1,
                    ])
                    ->where('co_inversionista', '!=', $p_inversionista->co_inversionista)
                ->first();
                if ( $proyectoAsignado ) {
                    Mail::to($p_inversionista->no_correo_electronico)->cc($analistas_emails)->send(new NotificacionProyectoAprobadoCola($prestamo->co_unico_solicitud, $p_inversionista->no_completo_persona, $analista->name, $prestamo->co_solicitud_prestamo, 2));

                    $response = [
                        'http_code' => 200,
                        'message'   => "Proyecto aprobado correctamente.",
                        'detail'    => "Estas en cola para el proyecto, eres el número: 2.",
                        'status'    => "Success",
                        'analista'  => $analista->nu_celular_trabajo,
                        'co_unico'  => $prestamo->co_unico_solicitud,
                        'prestamo'  => $request->codigo_prestamo,
                        'persona'   => Auth::user()->inversionista_id,
                        'total_aprobados' => $total_aprobados,
                        'enviar_wsp' => $enviar_wsp,
                    ];
                } else {

                    HOcurrenciaPrestamo::create([
                        'co_ocurrencia'       => 34,
                        'co_condicion'        => 341,
                        'co_prestamo'         => $request->codigo_prestamo,
                        'de_observacion'      => "Aprobado desde la plataforma de proyectos.",
                        'in_estado'           => 1,
                        'co_usuario_creacion' => null,
                        'fe_usuario_creacion' => now(),
                        'co_usuario_modifica' => null,
                        'fe_usuario_modifica' => now(),
                    ]);
                    PPrestamo::where('co_prestamo', $request->codigo_prestamo)
                        ->update([
                            'co_ocurrencia_actual' => 34,
                            'co_condicion_actual'  => 341,
                            'co_usuario_inversion'  => $inversionista_gestor->gestor,
                        ])
                    ;
        
                    RPrestamoInversionista::where(['co_prestamo' => $request->codigo_prestamo])->update(['in_estado' => 0]);
        
                    RPrestamoInversionista::create([
                        'co_prestamo'         => $request->codigo_prestamo,
                        'co_inversionista'    => $p_inversionista->co_inversionista, // codigo p_inversionista
                        'in_estado'           => 1,
                        'nu_porcentaje'       => 100,
                        'co_usuario_modifica' => null,
                        'fe_usuario_modifica' => now()
                    ]);
    
                    Mail::to($p_inversionista->no_correo_electronico)->cc($analistas_emails)->send(new NotificacionProyectoAprobado($prestamo->co_unico_solicitud, $p_inversionista->no_completo_persona, $analista->name, $prestamo->co_solicitud_prestamo));
        
                    if ($prestamo->co_unico_prestamo == '') {
        
                        $sede = $prestamo->co_sede;
                        $producto = $prestamo->co_producto;
        
                        $letra = 'P';
                        $numeroSede = str_pad($sede, 3, "0", STR_PAD_LEFT);
                        $numeroProducto = str_pad($producto, 3, "0", STR_PAD_LEFT);
        
                        $semilla = ASemillaPrestamo::where('in_estado', 1)->orderBy('nu_solicitud', 'DESC')->first();
                        $numero  = $semilla->nu_solicitud + 1;
        
                        ASemillaPrestamo::create([
                            'nu_solicitud'        => $numero,
                            'in_estado'           => 1,
                            'co_usuario_modifica' => null,
                            'fe_usuario_modifica' => now()
                        ]);
        
                        $codigoUnico = $letra . $numeroSede . '-' . $numeroProducto . '-' . $numero;
                        PPrestamo::where('co_prestamo', $request->codigo_prestamo)->update(['co_unico_prestamo' => $codigoUnico]);
                    }
        
                    $detalleEstado = HEstadoPrestamo::select('co_estado_prestamo')->orderBy('co_estado_prestamo', 'DESC')->first();
                    $cantidad = $detalleEstado->co_estado_prestamo + 1;
                    HEstadoPrestamo::create([
                        'co_estado_prestamo'  => $cantidad,
                        'co_prestamo'         => $request->codigo_prestamo,
                        'co_estado'           => 6, //INVERSIONISTA ASIGNADO
                        'in_estado'           => 1,
                        'co_usuario_creacion' => null,
                        'co_usuario_modifica' => null,
                        'fe_usuario_creacion' => now(),
                        'fe_usuario_modifica' => now(),
                    ]);
        
                    PNotificacion::create([
                        'co_usuario_notificacion' => $prestamo->co_usuario,
                        'de_tipo_notificacion'    => "Prestamo {$prestamo->co_unico_solicitud} se asigno inversionista",
                        'de_url'                  => url("/solicitantes-aceptados?solicitante=&codigo={$prestamo->co_unico_solicitud}&codigo_prestamo=&fecha_inicio=&fecha_fin="),
                        'co_tipo_notificacion'    => 2,
                        'in_estado'               => 1,
                        'co_usuario_modifica'     => null,
                        'fe_notificacion'         => now(),
                        'fe_usuario_modifica'     => now(),
                    ]);
        
                    HAuditoria::create([
                        'co_indice'           => $request->codigo_prestamo,
                        'no_auditoria'        => 'Se asigna proyecto desde la web de proyectos.',
                        'no_tabla'            => 'p_prestamo',
                        'co_usuario_modifica' => null,
                        'fe_usuario_modifica' => now(),
                    ]);
        
                    $response = [
                        'http_code' => 200,
                        'message'   => "Proyecto aprobado correctamente.",
                        'status'    => "Success",
                        'analista'  => $analista->nu_celular_trabajo,
                        'co_unico'  => $prestamo->co_unico_solicitud,
                        'prestamo'  => $request->codigo_prestamo,
                        'persona'   => Auth::user()->inversionista_id,
                        'total_aprobados' => $total_aprobados,
                        'enviar_wsp' => $enviar_wsp,
                    ];
                }
    
            }
            
            DB::commit();
            return response()->json($response);
            
        } catch (\Exception $e) {

            // Log::error('Error al asignar el proyecto: ' . $e->getMessage());
            $response = [
                'http_code' => 400,
                'message'   => "Error al asignar el proyecto: " . $e->getMessage(),
                'status'    => "Error",
            ];
            DB::rollBack();
            return response()->json($response);
        }
    }

    public function desaprobar_proyecto(Request $request)
    {
        $p_inversionista = PPersona::join('p_solicitud_inversionista AS soli', 'soli.co_persona', 'p_persona.co_persona')
            ->join('p_inversionista AS pi', 'pi.co_solicitud_inversionista', 'soli.co_solicitud_inversionista')
            ->where('soli.in_estado', 1)
            ->where('p_persona.in_estado', 1)
            ->where('p_persona.co_persona', Auth::user()->inversionista_id)
            ->select('pi.co_inversionista')
        ->first();
        
        // Validar que el usuario pueda deshabilitar la aprobación solo uego de 5min
        $haceCincoMinutos = Carbon::now()->subMinutes(5);
        $prestamo_inversion = RPrestamoInversionista::where([
                'co_prestamo'         => $request->codigo_prestamo,
                'co_inversionista'    => $p_inversionista->co_inversionista,
                'in_estado'           => 1,
                'nu_porcentaje'       => 100,
                'co_usuario_modifica' => null,
            ])
            ->where('fe_usuario_modifica', '<=', $haceCincoMinutos)
        ->first();
        if ( $prestamo_inversion ) {
            return response()->json([
                'http_code' => 400,
                'message'   => "Nota: El tiempo para desvincular el proyecto es de 5 minutos luego de haberlo aprobado, comuníquese con su analista para revertir la aprobación.",
                'status'    => "Error",
            ]);
        }

        // Validar que el proyecto no este asignado
        $proyectoAsignado = RPrestamoInversionista::where([
                'co_prestamo' => $request->codigo_prestamo,
                'in_estado'   => 1,
            ])
            ->where('co_inversionista', '!=', $p_inversionista->co_inversionista)
        ->first();
        if ( $proyectoAsignado ) {
            return response()->json([
                'http_code' => 400,
                'message'   => "No se puede desestimar el proyecto, esta aceptado por otra persona.",
                'status'    => "Error",
            ]);
        }

        try {
            DB::beginTransaction();

            HOcurrenciaPrestamo::create([
                'co_ocurrencia'       => 36,
                'co_condicion'        => 342,
                'co_prestamo'         => $request->codigo_prestamo,
                'de_observacion'      => "Desaprobado desde la plataforma de proyectos.",
                'in_estado'           => 1,
                'co_usuario_creacion' => null,
                'fe_usuario_creacion' => now(),
                'co_usuario_modifica' => null,
                'fe_usuario_modifica' => now(),
            ]);
            PPrestamo::where('co_prestamo', $request->codigo_prestamo)
                ->update([
                    'co_ocurrencia_actual' => 36,
                    'co_condicion_actual'  => 342,
                ])
            ;
            RPrestamoInversionista::where('co_prestamo', $request->codigo_prestamo)->update(['in_estado' => 0]);

            $response = [
                'http_code' => 200,
                'message'   => "Proyecto desvinculado correctamente.",
                'status'    => "Success",
            ];
            
            DB::commit();
            return response()->json($response, 200);
            
        } catch (\Exception $e) {

            // Log::error('Error al asignar el proyecto: ' . $e->getMessage());
            $response = [
                'http_code' => 400,
                'message'   => "Error al asignar el proyecto: " . $e->getMessage(),
                'status'    => "Error",
            ];
            DB::rollBack();
            return response()->json($response);
        }
    }
}
