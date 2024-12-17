<?php

namespace App\Http\Controllers\Api;

use App\ASemillaPrestamo;
use App\HAuditoria;
use App\HEstadoPrestamo;
use App\HOcurrenciaPrestamo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\InversionistaProyecto;
use App\Mail\NotificacionProyectoAprobado;
use App\Mail\NotificacionProyectoAprobadoCola;
use App\PInversionista;
use App\PNotificacion;
use App\PPersona;
use App\PPrestamo;
use App\RPrestamoInversionista;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AprobarProyectoController extends Controller
{
    public function aceptar_proyecto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'codigo_prestamo' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'http_code' => 422,
                'message'   => 'Errores de validación.',
                'errors'    => $validator->errors(),
                'status'    => 'Error',
            ], 422);
        }

        try {
            DB::beginTransaction();

            $user = User::find($request->user_id);
            if ( !$user ) {
                return response()->json([
                    'http_code' => 400,
                    'message'   => 'El usuario no existe.',
                    'status'    => 'Error',
                ]);
            }

            $max_prioridad_proyecto = InversionistaProyecto::where('prestamo_id', $request->codigo_prestamo)->max('prioridad');
            $prioridad = $max_prioridad_proyecto ? $max_prioridad_proyecto + 1 : 1;

            $exist_inversionista = InversionistaProyecto::where([
                    'prestamo_id' => $request->codigo_prestamo,
                    'persona_id' => $user->inversionista_id,
                    'estado' => 1,
                ])
            ->first();

            if ( $exist_inversionista ) {
                $response = [
                    'http_code' => 400,
                    'message'   => "Usted ya aprobó este proyecto.",
                    'status'    => "Error",
                ];
                DB::rollBack();
                return response()->json($response);
            }

            InversionistaProyecto::create([
                'prestamo_id' => $request->codigo_prestamo,
                'persona_id' => $user->inversionista_id,
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
                ->where('p_persona.co_persona', $user->inversionista_id)
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
                // ->where('co_perfil', 12)
                ->where('co_usuario', 42)
                ->where('in_estado', 1)
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

                // Verificar si existe cola, sin contar al mismo usuario porque ya se registró antes en esta tabla para llegar aqui 
                $existe_cola = InversionistaProyecto::where([
                        'prestamo_id' => $request->codigo_prestamo,
                        'estado' => 1,
                    ])
                    ->where('persona_id', '!=', $user->inversionista_id)
                ->first();
                
                if ( $existe_cola ) {
                    Mail::to($p_inversionista->no_correo_electronico)->cc($analistas_emails)->send(new NotificacionProyectoAprobadoCola($prestamo->co_unico_solicitud, $p_inversionista->no_completo_persona, $analista->name, $prestamo->co_solicitud_prestamo, $prioridad));
                    
                    $response = [
                        'http_code' => 200,
                        'message'   => "Proyecto aprobado correctamente.",
                        'detail'    => "Estas en cola para el proyecto, eres el número: $prioridad.",
                        'status'    => "Success",
                        'analista'  => $analista->nu_celular_trabajo,
                        'co_unico'  => $prestamo->co_unico_solicitud,
                        'prestamo'  => $request->codigo_prestamo,
                        'persona'   => $user->inversionista_id,
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
                        Mail::to($p_inversionista->no_correo_electronico)->cc($analistas_emails)->send(new NotificacionProyectoAprobadoCola($prestamo->co_unico_solicitud, $p_inversionista->no_completo_persona, $analista->name, $prestamo->co_solicitud_prestamo, $prioridad+1));

                        $response = [
                            'http_code' => 200,
                            'message'   => "Proyecto aprobado correctamente.",
                            'detail'    => "Estas en cola para el proyecto, eres el número: 2.",
                            'status'    => "Success",
                            'analista'  => $analista->nu_celular_trabajo,
                            'co_unico'  => $prestamo->co_unico_solicitud,
                            'prestamo'  => $request->codigo_prestamo,
                            'persona'   => $user->inversionista_id,
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
                            'persona'   => $user->inversionista_id,
                            'total_aprobados' => $total_aprobados,
                            'enviar_wsp' => $enviar_wsp,
                        ];
                    }

                }

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
                        'persona'   => $user->inversionista_id,
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
                        'persona'   => $user->inversionista_id,
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
}
