<?php

namespace App\Http\Controllers;

use App\MeInteresa;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
class InicioController extends Controller
{   

    public function index(Request $request){
               
        $distritos = DB ::table('p_distrito')
                        ->select('co_distrito','no_distrito')
                        ->get();
        $ubicacion = $request->distritos;
        $monto = $request->monto;

        $ocurrencias = [31, 32, 33, 36];
     
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
                'p_prestamo.co_ocurrencia_actual',
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
                'tipo_moneda_dato_prestamo.nc_tipo_moneda AS tipo_moneda_dato_prestamo',
                'valor_comercial_inmueble',
                DB::raw("(select url_evidencia from r_imagenes_inmueble imagen where imagen.co_solicitud_prestamo = p_solicitud_prestamo.co_solicitud_prestamo and in_estado = 1 order by id asc limit 1) as imagen_principal")
            )
            ->whereRaw("(select co_ocurrencia from h_ocurrencia_prestamo where h_ocurrencia_prestamo.co_prestamo  = p_prestamo.co_prestamo and h_ocurrencia_prestamo.in_estado = 1 
            order by co_ocurrencia_prestamo desc limit 1) IN (31, 32, 33, 36       ,34,45,53)")
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
                $like= MeInteresa::where(['co_prestamo'=> $solicitante->co_prestamo,
                'co_inversionista'=>Auth::user()->id])->first();
                if ($like) {
                    $solicitante->interesado = $like;
                }
                return $solicitante;
            });
            $solicitantesprocesados = collect($solicitantesprocesados);
            $perPage = 10; // Número de elementos por página (ajústalo según tus necesidades)
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

            $analista = DB::table('p_usuario')->join('p_solicitud_inversionista', 'p_solicitud_inversionista.co_usuario', 'p_usuario.co_usuario')
                                ->where('p_solicitud_inversionista.co_persona', Auth::user()->inversionista_id)
                                ->select(
                                    'name as analista_nombre',
                                    DB::raw("IFNULL(nu_celular_trabajo, '946038148') as celular"),
                                    'email as analista_email'
                                )
                                ->first();

            return view('giapp.inicio.inicio', compact('solicitantesprocesados', 'distritos','totalLikesPorPrestamo','ubicacion', 'monto', 'analista'));
    }

    public function meInteresa(Request $request)
    {
        $like= MeInteresa::where([
                'co_prestamo'=> $request->co_prestamo,
                'co_inversionista'=> Auth::user()->id,
            ])->first();


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
        ])->first();
    
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
 
}
