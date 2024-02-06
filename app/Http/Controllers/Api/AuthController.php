<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Config as ModelsConfig;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
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

        event(new Registered($user));

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
        echo json_encode($credentials);

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

    public function update(Request $request){
        $name = $request['name'];
        $alias = $request['alias'];
        $color = $request['color'];
        $type = $request['type'];
        $t_city = $request['t_city'];
        $t_municipality = $request['t_municipality'];
        $t_barangay = $request['t_barangay'];

        $user = Auth::user();
        $users = User::where('id', '=', $user->id)->update([
            'name' => $name,
            'alias' => $alias,
            'color' => $color,
            'type' => $type,
            'corvered_area_country' => 'Philippines',
            'corvered_area_city' => $t_city,
            'corvered_area_municipality' => $t_municipality,
            'corvered_area_barangay' => $t_barangay
        ]);

        Survey::create([
            'user_id' => $user->id,
            'name' => 'My first survey'
        ]);

        return redirect('/')->with('success', "Account successfully setup.");

    }
}
