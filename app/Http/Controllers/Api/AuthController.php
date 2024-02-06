<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Config as ModelsConfig;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    //
    public function register(RegisterRequest $request) 
    {
        $user = User::create($request->validated());

        ModelsConfig::create([
            "user_id" => $user->id
        ]);

        auth()->login($user);

        return redirect('/')->with('success', "Account successfully registered.");
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        $rememberMe = false;

        //return json_encode(Auth::validate($credentials));

        if(!Auth::validate($credentials)):
            return redirect()->to('login')
                ->withErrors(["failed" => ["Invalid email and password!"]]);
        endif;


        $user = Auth::getProvider()->retrieveByCredentials($credentials);

        Auth::login($user, $request->remember);

        return $this->authenticated($request, $user);
       //return redirect('/')->with('success', "Account successfully registered.");
    }

    protected function authenticated(Request $request, $user) 
    {
        return redirect()->intended();
    }

    public function logout()
    {
        Session::flush();
        
        Auth::logout();

        return redirect('login');
    }
}
