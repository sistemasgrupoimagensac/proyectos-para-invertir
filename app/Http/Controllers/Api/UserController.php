<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function user(Request $request)
    {
        return response()->json([
            'data'  => new UserResource($request->user()),
            'message' => 'Usuario obtenido',
            'success' => true,
        ]);
    }
}
