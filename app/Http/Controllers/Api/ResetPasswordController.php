<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\SendCredentialsInvestment;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function sendPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (null == $user) {
            return response()->json(['message' => 'Este correo no esta en los registros como inversionista'], 401);
        }

        $password = Str::random(8);
        $user->password = Hash::make($password);
        $user->save();

        Mail::to($request->email)
                ->send(new SendCredentialsInvestment($user->name, trim($request->email), $password));

        return response()->json([
            'message' => 'Los accesos fueron enviados al correo registrado'
        ]);
    }
}
