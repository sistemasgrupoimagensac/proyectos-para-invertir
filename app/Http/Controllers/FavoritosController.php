<?php

namespace App\Http\Controllers;

use App\MeInteresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FavoritosController extends Controller
{
    public function index(Request $request)
    {
        $provincias = DB::table('h_provincia')->select('co_provincia','no_provincia')->orderBy('no_provincia')->get();

        $favoritos= DB::table('me_interesa')
                    ->join('p_prestamo', 'me_interesa.co_prestamo', 'p_prestamo.co_prestamo')
                    ->join('p_solicitud_prestamo', 'p_solicitud_prestamo.co_solicitud_prestamo', 'p_prestamo.co_solicitud_prestamo')
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
                    ->where([
                        'p_solicitud_prestamo.in_estado' => 1,
                        'p_prestamo.in_estado' => 1,
                    ])
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
                        'me_interesa.estado',
                        'me_interesa.id as id_like',
                        'tipo_moneda_dato_prestamo.nc_tipo_moneda AS tipo_moneda_dato_prestamo',
                        'valor_comercial_inmueble',
                        DB::raw("(select url_evidencia from r_imagenes_inmueble imagen where imagen.co_solicitud_prestamo = p_solicitud_prestamo.co_solicitud_prestamo and in_estado = 1 order by id asc limit 1) as imagen_principal")
                    )
                    ->whereIn('a_tipo_credito.co_tipo_credito', [2, 3, 4])
                    ->whereRaw("p_prestamo.co_ocurrencia_actual IN (31, 32, 33, 36)")
                    // ->where(function($q) {
                    //     $q->whereNotIn('p_prestamo.co_condicion_actual', [58, 57])->orWhereNull('co_condicion_actual');
                    // })
                    ->whereRaw("(select url_evidencia from r_imagenes_inmueble imagen where imagen.co_solicitud_prestamo = p_solicitud_prestamo.co_solicitud_prestamo and in_estado = 1 order by id asc limit 1) IS NOT NULL")
                    ->where('me_interesa.co_inversionista', Auth::user()->id)
                    ->where('me_interesa.estado', 1)
                    ->orderBy('fe_solicitud_prestamo','desc')
                    ->where(function($query)use($request){
                        if($request->provincia){
                            $query->where('h_provincia.co_provincia', $request->provincia);
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
                    // ->paginate(20);
                    ->get();

            session(['provincia' => $request->input('provincia')]);
            session(['monto' => $request->input('monto')]);

            $analista = DB::table('p_usuario')->join('p_solicitud_inversionista', 'p_solicitud_inversionista.co_usuario', 'p_usuario.co_usuario')
                        ->where('p_solicitud_inversionista.co_persona', Auth::user()->inversionista_id)
                        ->select(
                            'name as analista_nombre',
                            DB::raw("IFNULL(nu_celular_trabajo, '946038148') as celular"),
                            'email as analista_email'
                        )
                        ->first();

            return view('giapp.inicio.favoritos', compact('favoritos', 'provincias', 'analista'));

    }

    public function cambiarEstado(Request $request)
    {
        $like = MeInteresa::find($request->id_like);

        if ($like != null) {
            $like->estado = 0; // Cambiar el estado a 0
            $like->save();
        }

        $response = [
            'like_actual' => $like
        ];

        return response()->json($response);
    }

}
