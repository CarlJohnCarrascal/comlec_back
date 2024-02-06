<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SurveyController extends Controller
{
    public function index() {
        return response()->json(Survey::all());
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required|unique:surveys'
        ]);

        if ($validator->fails()){
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 400);
        }

        $survey = Survey::create([
            "name" => $request['name'],
            "status" => ""
        ]);

        $response = [
            'success' => true,
            'message' => "Survey registered successfully",
            'data' => $survey
        ];

        return response()->json($response, 200);
    }

    public function destroy(Survey $survey) {
        $survey->delete();
        $response = [
            'success' => true,
            'message' => "Survey deleted successfully"
        ];
        return response()->json($response, 200);
    }

    public function markuse(Survey $survey) {
        Survey::where("isuse","=", true)->update(["isuse" => false]);
        Survey::where("id","=", $survey->id)->update(["isuse" => true]);
        //$survey->update(["isuse" => 1]);
        return response()->json(Survey::all());
    }
}