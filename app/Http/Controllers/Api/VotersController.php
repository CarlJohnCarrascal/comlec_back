<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\House;
use App\Models\Voters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class VotersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        try {
            $s = $request->search;
            $voters = Voters::where(function ($v) use ($s) {
                $v->where('fname', 'LIKE', '%'. $s .'%')
                    ->orWhere('lname', 'LIKE', '%'. $s .'%')
                    ->orWhere('mname', 'LIKE', '%'. $s .'%');
            });

            if($request->city !== 'all') $voters->where('city','=', $request->city['name']);
            if($request->municipality !== 'all') $voters->where('municipality','=', $request->municipality['name']);
            if($request->barangay !== 'all') $voters->where('barangay','=', $request->barangay['name']);
            if($request->purok !== 'all') $voters->where('purok','=', $request->purok);
            if($request->house_number !== 'all') $voters->where('house_number','=', $request->house_number['house_number']);

            
                $show = [];
            if($request->show['all'] == "false"){
                if($request->show['leader'] == "true") array_push($show, "Leader");
                if($request->show['right'] == "true") array_push($show, "Right");
                if($request->show['left'] == "true") array_push($show, "Left");
                if($request->show['undecided'] == "true") array_push($show, "Undecided");
                if($request->show['unmarked'] == "true") array_push($show, "");
                $voters->whereIn('mark', $show);
            }
            //return json_encode([$show, $request->show['all'] == "false"]);

            if($request->show['house_head'] == "true"){  
                $voters->where('ishead', true);
            }

            return response()->json($voters->paginate($request->item_per_page), 200);
        } catch (\Throwable $th) {
            return $th;
        }
        
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

        if($markAs == "Unmark") $markAs = "";
        //return response()->json(["success" => true, json_encode($markAs), json_encode($voters)],200);
        Voters::whereIn('id', $voters)->update(['mark' => $markAs]);
        // foreach($voters as $v){
        //     $voter = 
        //     $voter->update(['mark' => $markAs]);
        // }
        

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
        try {
        $city = $request->city;
        $municipality = $request->municipality;
        $barangay = $request->barangay;
        $purok = $request->purok;

        if($city !== "all") $city = $city['name'];
        if($municipality !== "all") $municipality = $municipality['name'];
        if($barangay !== "all") $barangay = $barangay['name'];
        //$house_number = $request->house_number;

        $lables = [];
        $dataset = ["leader" => [],"right" => [],"left" => [],"undecided" => [],"none" => [],"tright" => [],"tleft" => [],"tundecided" => [],"tnone" => []];
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
                //return json_encode($dataset);
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
            "leader" => array_sum($dataset['leader']),
            "right" => array_sum($dataset['right']) + array_sum($dataset['leader']),
            "left" => array_sum($dataset['left']),
            "undecided" => array_sum($dataset['undecided']),
            "unmarked" => array_sum($dataset['none'])
        ];

        return response()->json(["labels" => $lables,"datasets" => $dataset, "total" => $total]);
        
            //code...
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function getbarcharttotal() {
        //return DB::table('voters')->count();
        $total = [
            "voters"=> DB::table('voters')->count(),
            "leader"=> DB::table('voters')->where('mark', '=', 'Leader')->count(),
            "right"=> DB::table('voters')->where('mark', '=', 'Right')->count(),
            "left"=> DB::table('voters')->where('mark', '=', 'Left')->count(),
            "undecided"=> DB::table('voters')->where('mark', '=', 'Undecided')->count(),
            "unmarked"=> DB::table('voters')->where('mark', '=', '')->count()
        ];
        return response()->json(["total" => $total]);
    }

    public function get_house_member(Request $request){
        $house_id = $request->id;
        $member = Voters::where('house_id','=', $house_id)->get();
        return response()->json($member);
    }

    public function mark_as_head(Voters $voter) {
        Voters::where('house_id','=',$voter->house_id)->update(['ishead' => false]);
        $voter->update(['ishead' => true]);
        $member = Voters::where('house_id','=', $voter->house_id)->get();
        return response()->json($member);
    }
}
