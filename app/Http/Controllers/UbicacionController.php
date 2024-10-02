<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UbicacionController extends Controller
{
    public function index(Request $request)
    {
        $distritos = DB ::table('p_distrito')
                            ->select('co_distrito','no_distrito')
                            ->get();

        $solicitantes = DB::table('p_solicitud_prestamo')
                            ->join('p_persona', 'p_persona.co_persona', 'p_solicitud_prestamo.co_persona')
                            ->join('a_tiempo_pago', 'p_solicitud_prestamo.co_tiempo_pago', 'a_tiempo_pago.co_tiempo_pago')
                            ->join('a_forma_pago', 'p_solicitud_prestamo.co_forma_pago', 'a_forma_pago.co_forma_pago')
                            ->leftJoin('a_tipo_moneda', 'p_solicitud_prestamo.co_tipo_moneda', 'a_tipo_moneda.co_tipo_moneda')
                            ->join('p_prestamo', 'p_solicitud_prestamo.co_solicitud_prestamo', 'p_prestamo.co_solicitud_prestamo')
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
                            // ->whereIn('p_prestamo.co_estado', [4, 5])
                            ->select(
                                'no_forma_pago',
                                'no_tiempo_pago', 
                                'p_solicitud_prestamo.nu_total_solicitado', 
                                'p_solicitud_prestamo.co_solicitud_prestamo',
                                'p_prestamo.fe_usuario_modifica', 'p_prestamo.co_estado', 'a_estado.no_estado',
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
                                'me_interesa.estado', 'datos_prestamo.motivo_prestamo', 'position_latitud_inmueble as latitud', 'position_longitud_inmueble as longitud',
                                'tipo_moneda_dato_prestamo.nc_tipo_moneda AS tipo_moneda_dato_prestamo',
                                'valor_comercial_inmueble',
                                DB::raw("(select url_evidencia from r_imagenes_inmueble imagen where imagen.co_solicitud_prestamo = p_solicitud_prestamo.co_solicitud_prestamo and in_estado = 1 order by id asc limit 1) as imagen_principal")
                            )
                            ->whereRaw("(select co_ocurrencia from h_ocurrencia_prestamo where h_ocurrencia_prestamo.co_prestamo  = p_prestamo.co_prestamo and h_ocurrencia_prestamo.in_estado = 1 
                            order by co_ocurrencia_prestamo desc limit 1) IN (31, 32, 33, 36)")
                            ->whereRaw("(select url_evidencia from r_imagenes_inmueble imagen where imagen.co_solicitud_prestamo = p_solicitud_prestamo.co_solicitud_prestamo and in_estado = 1 order by id asc limit 1) IS NOT NULL")
                            ->orderBy('fe_solicitud_prestamo','desc')
                            ->get();
        
        return view('giapp.inicio.maps', compact('distritos', 'solicitantes'));
    }
}
