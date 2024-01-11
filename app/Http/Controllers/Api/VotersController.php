<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Voters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VotersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Voters::paginate(500), 200);
    }

    public function total_voters(){
        $total = Voters::all()->count();
        return response()->json(["total_voters" => $total],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Voters $voter)
    {
        return response()->json(["voter" => $voter],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function mark(Voters $voter, Request $request)
    {
        $markAs = $request->markas;
        $voter->update(["mark" => $markAs]);
        return response()->json(["voter" => $voter],200);
    }
    public function markselected(Request $request)
    {
        $markAs = $request->markas;
        $voters = json_decode($request->voters);

        //return response()->json(["success" => true, json_encode($markAs), json_encode($voters)],200);

        foreach($voters as $v){
            $voter = Voters::where('id','=', $v->id);
            $voter->update(['mark' => $markAs]);
        }

        return response()->json(["success" => true, json_encode($markAs), json_encode($voters)],200);
    }

    public function import(Request $request)
    {
        //
    }
    public function checkimport(Request $request)
    {
        //
    }

    public function getbarchart(Request $request) {
        $city = $request->city;
        $municipality = $request->municipality;
        $barangay = $request->barangay;
        $purok = $request->purok;
        $house_number = $request->house_number;
        $lables = [];
        $dataset = ["leader" => [],"right" => [],"left" => [],"undecided" => [],"none" => []];
        if($city == 'all'){
            $lables = Voters::select('city as label')->distinct()->get();
            for ($l=0; $l < count($lables); $l++) { 
                $data = DB::table('voters')
                ->selectRaw('mark, count(*) as total')
                ->where('city','=',$lables[$l]->label)
                ->groupBy('mark')->get();
                $ld = 0;
                $rd = 0;
                $led = 0;
                $ud = 0;
                $nd = 0;
                for ($i=0; $i < count($data); $i++) { 
                    if(strtolower($data[$i]->mark) == 'leader') $ld = $data[$i]->total;
                    if(strtolower($data[$i]->mark) == 'right') $rd = $data[$i]->total;
                    if(strtolower($data[$i]->mark) == 'left') $led = $data[$i]->total;
                    if(strtolower($data[$i]->mark) == 'undecided') $ud = $data[$i]->total;
                    if(strtolower($data[$i]->mark) == '') $nd = $data[$i]->total;
                }
                array_push($dataset['leader'], $ld);
                array_push($dataset['right'], $rd);
                array_push($dataset['left'], $led);
                array_push($dataset['undecided'], $ud);
                array_push($dataset['none'], $nd);
            }
        }
        if($city !== 'all' && $municipality == 'all'){
            $lables = Voters::select('municipality as label')->where('city', '=', $city)->distinct()->get();
            for ($l=0; $l < count($lables); $l++) { 
                $data = DB::table('voters')
                ->selectRaw('mark, count(*) as total')
                ->where('city','=',$city)
                ->where('municipality','=', $lables[$l]->label)
                ->groupBy('mark')->get();
                $ld = 0;
                $rd = 0;
                $led = 0;
                $ud = 0;
                $nd = 0;
                for ($i=0; $i < count($data); $i++) { 
                    if(strtolower($data[$i]->mark) == 'leader') $ld = $data[$i]->total;
                    if(strtolower($data[$i]->mark) == 'right') $rd = $data[$i]->total;
                    if(strtolower($data[$i]->mark) == 'left') $led = $data[$i]->total;
                    if(strtolower($data[$i]->mark) == 'undecided') $ud = $data[$i]->total;
                    if(strtolower($data[$i]->mark) == '') $nd = $data[$i]->total;
                }
                array_push($dataset['leader'], $ld);
                array_push($dataset['right'], $rd);
                array_push($dataset['left'], $led);
                array_push($dataset['undecided'], $ud);
                array_push($dataset['none'], $nd);
            }
        }
        if($city !== 'all' && $municipality !== 'all' && $barangay == 'all'){
            $lables = Voters::select('barangay as label')
            ->where('city', '=', $city)
            ->where('municipality', '=', $municipality)
            ->distinct()->get();

            for ($l=0; $l < count($lables); $l++) { 
                $data = DB::table('voters')
                ->selectRaw('mark, count(*) as total')
                ->where('city','=',$city)
                ->where('municipality','=', $municipality)
                ->where('barangay','=', $lables[$l]->label)
                ->groupBy('mark')->get();
                $ld = 0;
                $rd = 0;
                $led = 0;
                $ud = 0;
                $nd = 0;
                for ($i=0; $i < count($data); $i++) { 
                    if(strtolower($data[$i]->mark) == 'leader') $ld = $data[$i]->total;
                    if(strtolower($data[$i]->mark) == 'right') $rd = $data[$i]->total;
                    if(strtolower($data[$i]->mark) == 'left') $led = $data[$i]->total;
                    if(strtolower($data[$i]->mark) == 'undecided') $ud = $data[$i]->total;
                    if(strtolower($data[$i]->mark) == '') $nd = $data[$i]->total;
                }
                array_push($dataset['leader'], $ld);
                array_push($dataset['right'], $rd);
                array_push($dataset['left'], $led);
                array_push($dataset['undecided'], $ud);
                array_push($dataset['none'], $nd);
            }
        }
        
        if($city !== 'all' && $municipality !== 'all' && $barangay !== 'all' && $purok == 'all'){
            $lables = Voters::select('purok as label')
            ->where('city', '=', $city)
            ->where('municipality', '=', $municipality)
            ->where('barangay', '=', $barangay)
            ->distinct()->get();

            for ($l=0; $l < count($lables); $l++) { 
                $data = DB::table('voters')
                ->selectRaw('mark, count(*) as total')
                ->where('city','=',$city)
                ->where('municipality','=', $municipality)
                ->where('barangay','=', $barangay)
                ->where('purok','=', $lables[$l]->label)
                ->groupBy('mark')->get();
                $ld = 0;
                $rd = 0;
                $led = 0;
                $ud = 0;
                $nd = 0;
                for ($i=0; $i < count($data); $i++) { 
                    if(strtolower($data[$i]->mark) == 'leader') $ld = $data[$i]->total;
                    if(strtolower($data[$i]->mark) == 'right') $rd = $data[$i]->total;
                    if(strtolower($data[$i]->mark) == 'left') $led = $data[$i]->total;
                    if(strtolower($data[$i]->mark) == 'undecided') $ud = $data[$i]->total;
                    if(strtolower($data[$i]->mark) == '') $nd = $data[$i]->total;
                }
                array_push($dataset['leader'], $ld);
                array_push($dataset['right'], $rd);
                array_push($dataset['left'], $led);
                array_push($dataset['undecided'], $ud);
                array_push($dataset['none'], $nd);
            }
        }

        if($city !== 'all' && $municipality !== 'all' && $barangay !== 'all' && $purok !== 'all'){
            $lables = Voters::select('house_number as label')
            ->where('city', '=', $city)
            ->where('municipality', '=', $municipality)
            ->where('barangay', '=', $barangay)
            ->where('purok', '=', $purok)
            ->distinct()->get();

            for ($l=0; $l < count($lables); $l++) { 
                $data = DB::table('voters')
                ->selectRaw('mark, count(*) as total')
                ->where('city','=',$city)
                ->where('municipality','=', $municipality)
                ->where('barangay','=', $barangay)
                ->where('purok','=', $purok)
                ->where('house_number','=', $lables[$l]->label)
                ->groupBy('mark')->get();
                $ld = 0;
                $rd = 0;
                $led = 0;
                $ud = 0;
                $nd = 0;
                for ($i=0; $i < count($data); $i++) { 
                    if(strtolower($data[$i]->mark) == 'leader') $ld = $data[$i]->total;
                    if(strtolower($data[$i]->mark) == 'right') $rd = $data[$i]->total;
                    if(strtolower($data[$i]->mark) == 'left') $led = $data[$i]->total;
                    if(strtolower($data[$i]->mark) == 'undecided') $ud = $data[$i]->total;
                    if(strtolower($data[$i]->mark) == '') $nd = $data[$i]->total;
                }
                array_push($dataset['leader'], $ld);
                array_push($dataset['right'], $rd);
                array_push($dataset['left'], $led);
                array_push($dataset['undecided'], $ud);
                array_push($dataset['none'], $nd);
            }
        }

        $total = [
            "voters"=> Voters::all()->count(),
            "leader"=> array_sum($dataset['leader']),
            "right"=>array_sum($dataset['right']) + array_sum($dataset['leader']),
            "left"=>array_sum($dataset['left']),
            "undecided"=>array_sum($dataset['undecided']),
            "unmarked"=>array_sum($dataset['none'])
        ];

        return response()->json(["labels" => $lables,"datasets" => $dataset, "total" => $total]);
    }
}
