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

class AprobarProyectoController extends Controller
{
    public function aceptar_proyecto(Request $request)
    {
        $request->validate([
            'proyecto_id' => 'required|integer'
        ]);

        $prestamo = PPrestamo::join('p_solicitud_prestamo', 'p_solicitud_prestamo.co_solicitud_prestamo', 'p_prestamo.co_solicitud_prestamo')
                                ->where('p_solicitud_prestamo.co_solicitud_prestamo', $request->proyecto_id)->where('p_prestamo.in_estado', 1)
                                ->select('co_prestamo', 'co_unico_solicitud', 'co_usuario', 'co_unico_prestamo', 'co_sede', 'co_producto')
                                ->first();

        if (null == $prestamo) {
            return response()->json([
                'data'  => [],
                'message' => 'No existe el proyecto',
                'success' => false,
            ], 404);
        }

        try {
            DB::beginTransaction();

            // MAX aprobados
            $total_aprobados = 0;

            $proyectoAsignado = RPrestamoInversionista::where([
                    'co_prestamo' => $prestamo->co_prestamo,
                    'in_estado'   => 1,
                ])
            ->first();

            if ( $proyectoAsignado ) {

                $response = [
                    'message'   => "Este proyecto ya fue aprobado.",
                ];
                DB::rollBack();
                return response()->json($response, 400);
            }
            
            $p_inversionista = PPersona::join('p_solicitud_inversionista AS soli', 'soli.co_persona', 'p_persona.co_persona')
                ->join('p_inversionista AS pi', 'pi.co_solicitud_inversionista', 'soli.co_solicitud_inversionista')
                ->where('soli.in_estado', 1)
                ->where('p_persona.in_estado', 1)
                ->where('p_persona.co_persona', $request->user()->inversionista_id)
                ->select('pi.co_inversionista', 'p_persona.no_completo_persona', 'p_persona.no_correo_electronico')
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

            HOcurrenciaPrestamo::create([
                'co_ocurrencia'       => 34,
                'co_condicion'        => 341,
                'co_prestamo'         => $prestamo->co_prestamo,
                'de_observacion'      => "Aprobado desde la plataforma de proyectos.",
                'in_estado'           => 1,
                'co_usuario_creacion' => null,
                'fe_usuario_creacion' => now(),
                'co_usuario_modifica' => null,
                'fe_usuario_modifica' => now(),
            ]);
            PPrestamo::where('co_prestamo', $prestamo->co_prestamo)
                ->update([
                    'co_ocurrencia_actual' => 34,
                    'co_condicion_actual'  => 341,
                    'co_usuario_inversion'  => $inversionista_gestor->gestor,
                ])
            ;

            RPrestamoInversionista::where(['co_prestamo' => $prestamo->co_prestamo])->update(['in_estado' => 0]);

            RPrestamoInversionista::create([
                'co_prestamo'         => $prestamo->co_prestamo,
                'co_inversionista'    => $p_inversionista->co_inversionista, // codigo p_inversionista
                'in_estado'           => 1,
                'nu_porcentaje'       => 100,
                'co_usuario_modifica' => null,
                'fe_usuario_modifica' => now()
            ]);

            Mail::to($p_inversionista->no_correo_electronico)->cc($analistas_emails)->send(new NotificacionProyectoAprobado($prestamo->co_unico_solicitud, $p_inversionista->no_completo_persona, $analista->name, $request->proyecto_id));

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
                PPrestamo::where('co_prestamo', $prestamo->co_prestamo)->update(['co_unico_prestamo' => $codigoUnico]);
            }

            $detalleEstado = HEstadoPrestamo::select('co_estado_prestamo')->orderBy('co_estado_prestamo', 'DESC')->first();
            $cantidad = $detalleEstado->co_estado_prestamo + 1;
            HEstadoPrestamo::create([
                'co_estado_prestamo'  => $cantidad,
                'co_prestamo'         => $prestamo->co_prestamo,
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
                'co_indice'           => $prestamo->co_prestamo,
                'no_auditoria'        => 'Se asigna proyecto desde la web de proyectos.',
                'no_tabla'            => 'p_prestamo',
                'co_usuario_modifica' => null,
                'fe_usuario_modifica' => now(),
            ]);

            DB::commit();
            return response()->json([
                'data'  => [
                    'cantidad_aprobados'  => $total_aprobados,
                    'proyecto_id' => $request->proyecto_id,
                ],
                'message' => "Proyecto aprobado correctamente.",
                'success' => true,
            ]); 
        } catch (\Exception $e) {

            // Log::error('Error al asignar el proyecto: ' . $e->getMessage());
            $response = [
                'message'   => "Error al asignar el proyecto: " . $e->getMessage(),
            ];
            DB::rollBack();
            return response()->json($response, 400);
        }
    }
}
