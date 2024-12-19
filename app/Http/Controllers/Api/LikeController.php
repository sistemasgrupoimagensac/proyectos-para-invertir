<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\NotificarProyectoInteresado;
use App\MeInteresa;
use App\PPersona;
use App\PPrestamo;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class LikeController extends Controller
{
    // public function meInteresa(Request $request)
    // {
    //     $request->validate([
    //         'proyecto_id' => 'required|integer'
    //     ]);

    //     $like = MeInteresa::where([
    //                             'co_prestamo'=> $request->proyecto_id,
    //                             'co_inversionista'=> $request->user()->id,
    //                         ])
    //                         ->first();

    //     if ( $like ) {
    //         $like->estado = $like->estado == 1 ? 0 : 1;
    //         $like->fe_modificacion = now();
    //         $like->save();
    //     } else {
    //         $like = new MeInteresa();
    //         $like->co_prestamo = $request->proyecto_id;
    //         $like->comentario = '';
    //         $like->estado = 1;
    //         $like->co_inversionista = $request->user()->id;
    //         $like->fe_interaccion = now();
    //         $like->fe_modificacion = now();
    //         $like->save();
    //     }
        
    //     if ( $like->estado == 1 ) {
    //         $prestamo = PPrestamo::join('p_solicitud_prestamo', 'p_solicitud_prestamo.co_solicitud_prestamo', 'p_prestamo.co_solicitud_prestamo')
    //                                 ->where('co_prestamo', $request->proyecto_id)
    //                                 ->select('co_unico_solicitud')
    //                                 ->first();
    
    //         $inversionista = PPersona::join('p_solicitud_inversionista', 'p_solicitud_inversionista.co_persona', 'p_persona.co_persona')
    //             ->join('p_usuario', 'p_usuario.co_usuario', 'p_solicitud_inversionista.co_usuario')
    //             ->where('p_persona.co_persona', $user->inversionista_id)
    //             ->where('p_solicitud_inversionista.in_estado', 1)
    //             ->select('no_completo_persona', 'name', 'p_persona.nu_celular', 'p_usuario.email')
    //         ->first();
            
    //         $supervisores = DB::table('p_usuario')
    //             ->where('in_estado', 1)
    //             ->where('co_usuario', 42)
    //             ->select('email')
    //             ->pluck('email')
    //         ->toArray();

    //         Mail::to($inversionista->email)
    //             ->cc($supervisores)
    //         ->send(new NotificarProyectoInteresado($prestamo->co_unico_solicitud, $inversionista->no_completo_persona, $inversionista->name, $inversionista->nu_celular));
    //     }

    //     $cantidad = MeInteresa::where('co_prestamo', $request->co_prestamo)->where('estado', 1)->count();
        
    //     return response()->json([
    //         'http_code'   => 200,
    //         'message'     => 'Like asignado.',
    //         'status'      => 'Success',
    //         'like_actual' => $like,
    //         'cantidad'    => $cantidad,
    //     ]);
    // }

    public function noMeInteresa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'co_prestamo' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'http_code' => 422,
                'message'   => 'Errores de validación.',
                'errors'    => $validator->errors(),
                'status'    => 'Error',
            ], 422);
        }

        $user = User::find($request->user_id);
        if ( !$user ) {
            return response()->json([
                'http_code' => 400,
                'message'   => 'El usuario no existe.',
                'status'    => 'Error',
            ]);
        }

        $like = MeInteresa::where([
                'co_prestamo' => $request->co_prestamo,
                'co_inversionista' => $request->user_id,
            ])
        ->first();
    
        if ( $like != null ) {
            $like->estado = 0;
            $like->fe_modificacion = now();
            $like->save();
        }

        $cantidad = MeInteresa::where('co_prestamo', $request->co_prestamo)->where('estado', 1)->count();

        return response()->json([
            'http_code'   => 200,
            'message'     => 'Dislike asignado.',
            'status'      => 'Success',
            'like_actual' => $like,
            'cantidad'    => $cantidad,
        ]);
    }
}
