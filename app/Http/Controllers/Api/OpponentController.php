<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Opponent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OpponentController extends Controller
{
    public function index() {
        return response()->json(Opponent::all());
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required|unique:opponents',
            'color' => 'required|unique:opponents',
            'alias' => 'required|unique:opponents'
        ]);

        if ($validator->fails()){
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 400);
        }

        $survey = Opponent::create([
            "name" => $request['name'],
            "color" => $request['color'],
            "alias" =>  $request['alias']
        ]);

        $response = [
            'success' => true,
            'message' => "Opponent registered successfully",
            'data' => $survey
        ];

        return response()->json($response, 200);
    }

    public function destroy(Opponent $opponent) {
        $opponent->delete();
        $response = [
            'success' => true,
            'message' => "opponent deleted successfully"
        ];
        return response()->json($response, 200);
    }

    public function changecolor(Request $request, Opponent $opponent){
        //$id = $request->id;
        $color = $request->color;
        $opponent->update(['color'=> $color]);
        return response()->json($opponent, 200);
    }

}
