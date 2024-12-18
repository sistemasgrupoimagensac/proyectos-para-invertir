<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\InversionistaProyecto;
use App\MeInteresa;
use App\RPrestamoInversionista;
use Illuminate\Support\Facades\DB;

class AllProjectsController extends Controller
{
    public function getAll(Request $request)
    {
        $solicitantes = DB::table('p_solicitud_prestamo')
            ->join('a_tiempo_pago', 'p_solicitud_prestamo.co_tiempo_pago', 'a_tiempo_pago.co_tiempo_pago')
            ->join('a_forma_pago', 'p_solicitud_prestamo.co_forma_pago', 'a_forma_pago.co_forma_pago')
            ->leftJoin('a_tipo_moneda', 'p_solicitud_prestamo.co_tipo_moneda', 'a_tipo_moneda.co_tipo_moneda')
            ->join('p_prestamo', 'p_solicitud_prestamo.co_solicitud_prestamo', 'p_prestamo.co_solicitud_prestamo')
            ->leftJoin('p_distrito', 'p_distrito.co_distrito', 'p_solicitud_prestamo.co_distrito')
            ->leftJoin('h_provincia', 'h_provincia.co_provincia', 'p_distrito.co_provincia')
            ->leftJoin('a_tipo_garantia','p_solicitud_prestamo.co_tipo_garantia','a_tipo_garantia.co_tipo_garantia')
            ->leftJoin('a_tipo_cliente','p_prestamo.co_tipo_cliente','a_tipo_cliente.co_tipo_cliente')
            ->leftJoin('datos_prestamo', 'datos_prestamo.co_solicitud_prestamo', 'p_solicitud_prestamo.co_solicitud_prestamo')
            ->leftJoin('a_tipo_moneda AS tipo_moneda_dato_prestamo', 'datos_prestamo.co_tipo_moneda', 'tipo_moneda_dato_prestamo.co_tipo_moneda')
            ->where([
                'p_solicitud_prestamo.in_estado' => 1,
                'p_prestamo.in_estado' => 1,
            ])
            ->select(
                'p_solicitud_prestamo.co_solicitud_prestamo',
                DB::raw("(select url_evidencia from r_imagenes_inmueble imagen where imagen.co_solicitud_prestamo = p_solicitud_prestamo.co_solicitud_prestamo and in_estado = 1 order by id asc limit 1) as imagen_principal"),
                'no_distrito',
                'no_provincia',
                'co_unico_solicitud',
                'a_tipo_garantia.no_tipo_garantia',
                'a_tipo_cliente.nu_tasa_interes_mensual',
                'no_tiempo_pago',
                'no_forma_pago',
                'tipo_moneda_dato_prestamo.nc_tipo_moneda AS tipo_moneda_dato_prestamo',
                'valor_comercial_inmueble',
                'a_tipo_moneda.nc_tipo_moneda',
                'p_solicitud_prestamo.nu_total_solicitado',
                'p_prestamo.co_prestamo',
                DB::raw('IFNULL(position_latitud_inmueble, -12.0973182) as latitud'),
                DB::raw('IFNULL(position_longitud_inmueble, -77.0233135) as longitud'),
                DB::raw("(SELECT COUNT(*) FROM me_interesa WHERE me_interesa.co_prestamo = p_prestamo.co_prestamo AND me_interesa.estado = 1) AS likes")
            )
            ->whereRaw("p_prestamo.co_ocurrencia_actual IN (31, 32, 33, 34, 36)")
            ->where(function($q) {
                $q->where('p_prestamo.co_condicion_actual', '!=', 58)->orWhereNull('co_condicion_actual');
            })
            ->whereRaw("(select url_evidencia from r_imagenes_inmueble imagen where imagen.co_solicitud_prestamo = p_solicitud_prestamo.co_solicitud_prestamo and in_estado = 1 order by id asc limit 1) IS NOT NULL")
            ->where(function($query)use($request){
                if($request->provincia){
                    $query->where('h_provincia.co_provincia',$request->provincia);
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

        $solicitantes = $solicitantes->map(function($solicitante) {
            $solicitante->total_aprobados_proyecto = 0;
            $co_persona_asignada = 0;
            $proyectoAsignado = RPrestamoInversionista::join('p_inversionista AS pi', 'pi.co_inversionista', 'r_prestamo_inversionista.co_inversionista')
                            ->join('p_solicitud_inversionista AS soli', 'soli.co_solicitud_inversionista', 'pi.co_solicitud_inversionista')
                            ->join('p_persona AS p', 'p.co_persona', 'soli.co_persona')
                            ->where('r_prestamo_inversionista.co_prestamo', $solicitante->co_prestamo)->where('r_prestamo_inversionista.in_estado', 1)
                            ->select('p.co_persona')
                            ->first();
            
            if ($proyectoAsignado) {
                $solicitante->total_aprobados_proyecto += 1;
                $co_persona_asignada = $proyectoAsignado->co_persona;
            }

            $cant_aprobados_plataforma = InversionistaProyecto::where('prestamo_id', $solicitante->co_prestamo)
                                            ->where('persona_id', '<>', $co_persona_asignada)
                                            ->count();
            if ( $cant_aprobados_plataforma ) {
                $solicitante->total_aprobados_proyecto += $cant_aprobados_plataforma;
            }

            return $solicitante;
        });

        return response()->json([
            'data'  => ProjectResource::collection($solicitantes),
            'mesage' => 'Registros obtenidos',
            'success' => true,
        ]);
    }
}
