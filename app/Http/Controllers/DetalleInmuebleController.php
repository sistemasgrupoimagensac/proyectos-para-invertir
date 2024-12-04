<?php

namespace App\Http\Controllers;

use App\InversionistaProyecto;
use App\MeInteresa;
use App\PPersona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DetalleInmuebleController extends Controller
{
    public function index(Request $request,$co_solicitud_prestamo){
        
        $provincias = DB::table('h_provincia')->select('co_provincia','no_provincia')->orderBy('no_provincia')->get();

        $detalle = DB::table('p_solicitud_prestamo')
            ->join('p_prestamo', 'p_solicitud_prestamo.co_solicitud_prestamo', 'p_prestamo.co_solicitud_prestamo')                
            ->leftJoin('r_prestamo_inversionista', function($join) {
                $join->on('r_prestamo_inversionista.co_prestamo', '=', 'p_prestamo.co_prestamo')
                    ->where('r_prestamo_inversionista.in_estado', '=', 1);
            }) 
            ->join('a_tiempo_pago', 'p_solicitud_prestamo.co_tiempo_pago', 'a_tiempo_pago.co_tiempo_pago')
            ->join('a_forma_pago', 'p_solicitud_prestamo.co_forma_pago', 'a_forma_pago.co_forma_pago')
            ->leftJoin('a_tipo_moneda', 'p_solicitud_prestamo.co_tipo_moneda', 'a_tipo_moneda.co_tipo_moneda')
            ->join('a_estado', 'p_prestamo.co_estado', 'a_estado.co_estado')
            ->leftJoin('p_cartera', 'p_prestamo.co_prestamo', 'p_cartera.co_prestamo')
            ->leftJoin('p_distrito', 'p_distrito.co_distrito', 'p_solicitud_prestamo.co_distrito')
            ->leftJoin('h_provincia', 'h_provincia.co_provincia', 'p_distrito.co_provincia')
            ->leftJoin('a_tipo_credito', 'p_solicitud_prestamo.co_tipo_credito', 'a_tipo_credito.co_tipo_credito')
            ->leftJoin('a_tipo_garantia','p_solicitud_prestamo.co_tipo_garantia','a_tipo_garantia.co_tipo_garantia')
            ->leftJoin('a_tipo_cliente','p_prestamo.co_tipo_cliente','a_tipo_cliente.co_tipo_cliente')
            ->leftJoin('datos_prestamo', 'datos_prestamo.co_solicitud_prestamo', 'p_solicitud_prestamo.co_solicitud_prestamo')
            ->leftJoin('a_tipo_moneda AS tipo_moneda_dato_prestamo', 'datos_prestamo.co_tipo_moneda', 'tipo_moneda_dato_prestamo.co_tipo_moneda')
            ->leftJoin('p_usuario as ventas', 'ventas.co_usuario', 'p_prestamo.co_usuario')
            ->where([
                'p_solicitud_prestamo.in_estado' => 1,
                'p_solicitud_prestamo.co_solicitud_prestamo'=> $co_solicitud_prestamo
            ])
            ->whereIn('p_prestamo.co_estado', [4, 5])
            ->select(
                'no_forma_pago',
                'no_tiempo_pago', 'ventas.name as vendedor',
                'p_solicitud_prestamo.nu_total_solicitado', 
                'p_solicitud_prestamo.co_solicitud_prestamo',
                'p_prestamo.fe_usuario_modifica', 
                'p_prestamo.co_estado', 'a_estado.no_estado',
                'p_prestamo.co_prestamo',  
                'p_prestamo.co_ocurrencia_actual',  
                'r_prestamo_inversionista.co_inversionista',
                'nu_monto_prestamo',
                'a_tipo_moneda.nc_tipo_moneda',
                'co_unico_solicitud', 'co_unico_prestamo', 
                'fe_solicitud_prestamo',
                'no_distrito', 
                'no_provincia',
                'a_tipo_credito.no_tipo_credito',
                'a_tipo_garantia.no_tipo_garantia',
                'a_tipo_garantia.co_tipo_garantia',
                'a_tipo_cliente.nu_tasa_interes_mensual',
                'datos_prestamo.motivo_prestamo', 
                'position_latitud_inmueble as latitud', 
                'position_longitud_inmueble as longitud',
                'tipo_moneda_dato_prestamo.nc_tipo_moneda AS tipo_moneda_dato_prestamo',
                'valor_comercial_inmueble',
                DB::raw("(SELECT COUNT(*) FROM me_interesa WHERE p_prestamo.co_prestamo = me_interesa.co_prestamo AND me_interesa.estado = 1) AS interesados")
            )
        ->first();

        if (null == $detalle) {
            abort(404);
        }

        $aprobadoPorUserActual = false;
        $p_inversionista = PPersona::join('p_solicitud_inversionista AS soli', 'soli.co_persona', 'p_persona.co_persona')
            ->join('p_inversionista AS pi', 'pi.co_solicitud_inversionista', 'soli.co_solicitud_inversionista')
            ->where('soli.in_estado', 1)
            ->where('p_persona.in_estado', 1)
            ->where('p_persona.co_persona', Auth::user()->inversionista_id)
            ->select('pi.co_inversionista')
        ->first();
        if ( $p_inversionista && $detalle->co_inversionista == $p_inversionista->co_inversionista ) {
            $aprobadoPorUserActual = true;
        }

        $inversionista_proyecto = InversionistaProyecto::where([
                'prestamo_id' => $detalle->co_prestamo,
                'persona_id'  => Auth::user()->inversionista_id,
                'estado'      => 1,
            ])
        ->first('prioridad');
        if ( $inversionista_proyecto ) {
            $aprobadoPorUserActual = true;
        }

        $analista_inversion = DB::table('p_usuario')->join('p_solicitud_inversionista', 'p_solicitud_inversionista.co_usuario', 'p_usuario.co_usuario')
            ->where('p_solicitud_inversionista.co_persona', Auth::user()->inversionista_id)
            ->select(
                'name as analista_nombre',
                DB::raw("IFNULL(nu_celular_trabajo, '946038148') as analista_celular"),
                'email as analista_email'
            )
        ->first();

        $imagenes = DB::table('r_imagenes_inmueble')->where('co_solicitud_prestamo', $co_solicitud_prestamo)->where('in_estado', 1)->select('url_evidencia')->get();

        $like = MeInteresa::where([
                'co_prestamo'=> $detalle->co_prestamo,
                'co_inversionista'=>Auth::user()->id
            ])
        ->first();
        
        $detalle->interesado = $like;
                
        return view('giapp.inicio.detalle', compact('detalle', 'provincias', 'imagenes', 'analista_inversion', 'aprobadoPorUserActual'));
    }
}
