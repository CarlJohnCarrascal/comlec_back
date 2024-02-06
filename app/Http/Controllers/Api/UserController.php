<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\Config;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function update(UserRequest $request) {
        $user = Auth::user();
        $s = User::where('id', '=', $user->id)
        ->update([
            'name' =>  $request['name'],
            'alias' =>  $request['alias'],
            'color' =>  $request['color']
        ]);
        $c = Config::where('user_id', '=', $user->id)->update(['right_color' => $request['color']]);

        return response([$user->color, $request['color'], $s, $c]);
    }
}
