<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProvinciaResource;
use Illuminate\Support\Facades\DB;

class ProvinciasController extends Controller
{
    public function getAll(Request $request)
    {
        $provincias = DB::table('h_provincia')->select('co_provincia','no_provincia')->orderBy('no_provincia')->get();
        return response()->json([
            'data'  => ProvinciaResource::collection($provincias),
            'mesage' => 'Registros obtenidos',
            'success' => true,
        ]);
    }
}
