<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendCredentialsInvestment;
use App\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function reset()
    {
        return view('login.reset-password');
    }

    public function sendPassword(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (null == $user) {
            return back()->withErrors(['usuario_invalido' => 'Este correo no esta en los registros como inversionista'])->withInput();
        }

        $password = Str::random(8);
        $user->password = Hash::make($password);
        $user->save();

        Mail::to($request->email)
                ->send(new SendCredentialsInvestment($user->name, trim($request->email), $password));

        return redirect('/')->with('password_enviado', 'Los accesos fueron enviados al correo registrado');
    }
}
