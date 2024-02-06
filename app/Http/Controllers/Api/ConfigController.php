<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConfigController extends Controller
{
    public function index() {
        $id = Auth::user()->id;
        $configs = Config::where('user_id', '=', $id)->first();
        if($configs == null){
            $configs = $this->createConfig($id);
        }
        return response()->json($configs, 200);
    }

    public function update(Request $request) {
        $toUpdate = $request['toupdate'];
        $pc = $request['right_color'];
        $lc = $request['left_color'];
        $uc = $request['undecided_color'];
        $umc = $request['undecided_color'];

        $id = Auth::user()->id;
        $configs = Config::where('user_id', '=', $id)->first();
        if($configs == null){
            $configs = $this->createConfig($id);
        }
        $configs->update([
            'right_color' => $pc,
            'left_color' => $lc,
            'undecided_color' => $uc,
            'undecided_color' => $umc
        ]);
        
        User::where('id','=', $configs->user_id)->update(['color' => $pc]);
        return response()->json(["Update success.", $request->all()], 200);
    }

    public function createConfig($id): Config{
        return Config::create([
            'user_id' => $id,
        ]);
    }
}
