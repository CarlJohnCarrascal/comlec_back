<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\House;
use App\Models\Mark;
use App\Models\Opponent;
use App\Models\Survey;
use App\Models\Voters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class VotersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userid = Auth::user()->id;
        $surveyid = Survey::where('user_id','=',$userid)->first()->id;
        //$opponents = Opponent::where('user_id','=',$userid)->get();

        try {
            $s = "";
            if(!$request['search'] !== "") {
                $s = $request['search'];
            }
            $voters = DB::table('voters')->leftJoin('marks','marks.voters_id', '=','voters.id')
                    ->where('marks.survey_id', '=', $surveyid)
                    ->where(function ($v) use ($s) {
                $v->where('voters.fname', 'like', "%". $s . "%")
                    ->orWhere('voters.lname', 'like', "%". $s ."%")
                    ->orWhere('voters.mname', 'like', "%". $s ."%");
            });

            if($request->city !== 'all') $voters->where('voters.city','=', $request->city['name']);
            if($request->municipality !== 'all') $voters->where('voters.municipality','=', $request->municipality['name']);
            if($request->barangay !== 'all') $voters->where('voters.barangay','=', $request->barangay['name']);
            if($request->purok !== 'all') $voters->where('voters.purok','=', $request->purok);
            if($request->house_number !== 'all') $voters->where('voters.house_number','=', $request->house_number['house_number']);

            
                $show = [];
            if($request->show['all'] == "false"){
                if($request->show['leader'] == "true") array_push($show, "Leader");
                if($request->show['right'] == "true") array_push($show, "Me");
                if($request->show['undecided'] == "true") array_push($show, "Undecided");
                if($request->show['unmarked'] == "true") array_push($show, "");

                foreach($request['show']['opponents'] as $op) {
                    $name = $op['alias'];
                    if($op['check'] == "true") array_push($show, $name);
                }


                $voters->whereIn('marks.mark', $show);
            }
            //return json_encode([$show, $request->show['all'] == "false"]);

            if($request->show['house_head'] == "true"){  
                $voters->where('voters.ishead', true);
            }
            //$voters->orderBy('voters.fname');
            //return $voters->limit(10)->toSql();
            $list = $voters->orderBy('fname')->paginate($request->item_per_page, ['*'], "page", $request->page);

            return response()->json($list, 200);
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

        $userid = Auth::user()->id;
        $surveyid = Survey::where('user_id','=',$userid)->where('isuse', '=', true)->first()->id;

        if($markAs == "Unmark") $markAs = "";
        $data = [];
        
        foreach ($voters as $v) {
            //$d = ['survey_id' => $surveyid, 'voters_id' => $v, 'mark' => $markAs];
            //array_push($data, $d);
            Mark::updateOrCreate(
                ['survey_id' => $surveyid, 'voters_id' => $v],
                ['mark' => $markAs]
            );
        }
        
        //$marks = Mark::upsert($data,['survey_id', 'voters_id'], ['mark']);
        //Voters::whereIn('id', $voters)->update(['mark' => $markAs]);

        return response()->json(["success" => true, $data],200);
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
        //return response()->json($request->all());
        $city = $request->city;
        $municipality = $request->municipality;
        $barangay = $request->barangay;
        $purok = $request->purok;

        if($city !== "all") $city = $city['name'];
        if($municipality !== "all") $municipality = $municipality['name'];
        if($barangay !== "all") $barangay = $barangay['name'];
        //$house_number = $request->house_number;
        $userid = Auth::user()->id;
        $surveyid = Survey::where('user_id','=',$userid)->where('isuse', '=', true)->first()->id;
        $opponents = Opponent::where('user_id','=',$userid)->get();

        $lables = [];
        $dataset = ["leader" => [],"right" => [], "opponent" => [],"left" => [],"undecided" => [],"unmarked" => []];
        $total = [];

        foreach($opponents as $op) {

            //return response($op->alias);
            $dataset['opponent'][$op->alias] = [];
            //array_push($dataset, [$op->alias => []]);
        }
        
        //return response($dataset);

        if($city == 'all'){
            $lables = Voters::select('city as label')->distinct()->get();
            $lables = Voters::select('city as label')->where('city', '=', $city)->distinct()->get();
            $data = DB::table('voters')->rightJoin('marks','marks.voters_id','voters.id')
                ->selectRaw('voters.city as label, count(voters.id) as total, CASE WHEN marks.`mark` = "" THEN "Unmarked" ELSE marks.`mark` END AS mark')
                ->groupBy(['voters.city','marks.mark'])->get()->toArray();
                //return response($data->pluck('Unmarked','mark'));
            foreach($lables as $label) {                                                  
                $ld = array_filter($data, function ($d) use ($label) {
                    //echo $label;
                    return $d->label == $label->label && $d->mark == 'Leader';
                });
                $rd = array_filter($data, function ($d) use ($label) {
                    //echo $label;
                    return $d->label == $label->label && $d->mark == 'Me';
                });
                $tt = 0;
                foreach($opponents as $op) {
                    $da = array_filter($data, function ($d) use ($label, $op) {
                        //echo $label;
                        return $d->label == $label->label && $d->mark == $op->alias;
                    });
                    $t = 0;
                    if($da != false){
                        array_push($dataset['opponent'][$op->alias], reset($da)->total); 
                        $t = reset($da)->total;
                    }
                    else {
                        array_push($dataset['opponent'][$op->alias], 0);
                    }
                    $tt = $tt + $t;
                }

                $led = array_filter($data, function ($d) use ($label) {
                    //echo $label;
                    return $d->label == $label->label && $d->mark == 'Leader';
                });
                $ud = array_filter($data, function ($d) use ($label) {
                    //echo $label;
                    return $d->label == $label->label && $d->mark == 'Undecided';
                });
                $nd = array_filter($data, function ($d) use ($label) {
                    //echo $label;
                    return $d->label == $label->label && $d->mark == 'Unmarked';
                });

                if($ld != false) array_push($dataset['leader'], reset($ld)->total);
                else array_push($dataset['leader'], 0);
                if($rd != false)  array_push($dataset['right'], reset($rd)->total);
                else array_push($dataset['right'], 0);
                if($ud != false) array_push($dataset['undecided'], reset($ud)->total);
                else array_push($dataset['undecided'], 0);
                if($nd != false) array_push($dataset['unmarked'], reset($nd)->total);
                else array_push($dataset['unmarked'], 0);

                array_push($dataset['left'], $tt);
            }
        }
        if($city !== 'all' && $municipality == 'all'){
            $lables = Voters::select('municipality as label')->where('city', '=', $city)->distinct()->get();
            $data = DB::table('voters')->rightJoin('marks','marks.voters_id','voters.id')
                ->selectRaw('voters.municipality as label, count(voters.id) as total, CASE WHEN marks.`mark` = "" THEN "Unmarked" ELSE marks.`mark` END AS mark')
                ->where('voters.city','=',$city)
                ->groupBy(['voters.municipality','marks.mark'])->get()->toArray();
                //return response($data->pluck('Unmarked','mark'));
            foreach($lables as $label) {                                                  
                $ld = array_filter($data, function ($d) use ($label) {
                    //echo $label;
                    return $d->label == $label->label && $d->mark == 'Leader';
                });
                $rd = array_filter($data, function ($d) use ($label) {
                    //echo $label;
                    return $d->label == $label->label && $d->mark == 'Me';
                });
                $tt = 0;
                foreach($opponents as $op) {
                    $da = array_filter($data, function ($d) use ($label, $op) {
                        //echo $label;
                        return $d->label == $label->label && $d->mark == $op->alias;
                    });
                    $t = 0;
                    if($da != false){
                        array_push($dataset['opponent'][$op->alias], reset($da)->total); 
                        $t = reset($da)->total;
                    }
                    else {
                        array_push($dataset['opponent'][$op->alias], 0);
                    }
                    $tt = $tt + $t;
                }

                $led = array_filter($data, function ($d) use ($label) {
                    //echo $label;
                    return $d->label == $label->label && $d->mark == 'Leader';
                });
                $ud = array_filter($data, function ($d) use ($label) {
                    //echo $label;
                    return $d->label == $label->label && $d->mark == 'Undecided';
                });
                $nd = array_filter($data, function ($d) use ($label) {
                    //echo $label;
                    return $d->label == $label->label && $d->mark == 'Unmarked';
                });

                if($ld != false) array_push($dataset['leader'], reset($ld)->total);
                else array_push($dataset['leader'], 0);
                if($rd != false)  array_push($dataset['right'], reset($rd)->total);
                else array_push($dataset['right'], 0);
                if($ud != false) array_push($dataset['undecided'], reset($ud)->total);
                else array_push($dataset['undecided'], 0);
                if($nd != false) array_push($dataset['unmarked'], reset($nd)->total);
                else array_push($dataset['unmarked'], 0);

                array_push($dataset['left'], $tt);
            }
        }
        if($city !== 'all' && $municipality !== 'all' && $barangay == 'all'){
            $lables = Voters::select('barangay as label')
            ->where('city', '=', $city)
            ->where('municipality', '=', $municipality)
            ->distinct()->get();

            $data = DB::table('voters')->rightJoin('marks','marks.voters_id','voters.id')
                ->selectRaw('voters.barangay as label, count(voters.id) as total, CASE WHEN marks.`mark` = "" THEN "Unmarked" ELSE marks.`mark` END AS mark')
                ->where('voters.city','=',$city)
                ->where('voters.municipality','=', $municipality)
                ->groupBy(['voters.barangay','marks.mark'])->get()->toArray();
                //return response($data->pluck('Unmarked','mark'));
            foreach($lables as $label) {                                                  
                $ld = array_filter($data, function ($d) use ($label) {
                    //echo $label;
                    return $d->label == $label->label && $d->mark == 'Leader';
                });
                $rd = array_filter($data, function ($d) use ($label) {
                    //echo $label;
                    return $d->label == $label->label && $d->mark == 'Me';
                });
                $tt = 0;
                foreach($opponents as $op) {
                    $da = array_filter($data, function ($d) use ($label, $op) {
                        //echo $label;
                        return $d->label == $label->label && $d->mark == $op->alias;
                    });
                    $t = 0;
                    if($da != false){
                        array_push($dataset['opponent'][$op->alias], reset($da)->total); 
                        $t = reset($da)->total;
                    }
                    else {
                        array_push($dataset['opponent'][$op->alias], 0);
                    }
                    $tt = $tt + $t;
                }

                $led = array_filter($data, function ($d) use ($label) {
                    //echo $label;
                    return $d->label == $label->label && $d->mark == 'Leader';
                });
                $ud = array_filter($data, function ($d) use ($label) {
                    //echo $label;
                    return $d->label == $label->label && $d->mark == 'Undecided';
                });
                $nd = array_filter($data, function ($d) use ($label) {
                    //echo $label;
                    return $d->label == $label->label && $d->mark == 'Unmarked';
                });

                if($ld != false) array_push($dataset['leader'], reset($ld)->total);
                else array_push($dataset['leader'], 0);
                if($rd != false)  array_push($dataset['right'], reset($rd)->total);
                else array_push($dataset['right'], 0);
                if($ud != false) array_push($dataset['undecided'], reset($ud)->total);
                else array_push($dataset['undecided'],0);
                if($nd != false) array_push($dataset['unmarked'], reset($nd)->total);
                else array_push($dataset['unmarked'], 0);

                array_push($dataset['left'], $tt);
            }
        }
        
        if($city !== 'all' && $municipality !== 'all' && $barangay !== 'all' && $purok == 'all'){
            $lables = Voters::select('purok as label')
            ->where('city', '=', $city)
            ->where('municipality', '=', $municipality)
            ->where('barangay', '=', $barangay)
            ->distinct()->get();

            $data = DB::table('voters')->rightJoin('marks','marks.voters_id','voters.id')
            ->selectRaw('voters.purok as label, count(voters.id) as total, CASE WHEN marks.`mark` = "" THEN "Unmarked" ELSE marks.`mark` END AS mark')
            ->where('voters.city','=',$city)
            ->where('voters.municipality','=', $municipality)
            ->where('voters.barangay','=', $barangay)
            ->groupBy(['voters.purok','marks.mark'])->get()->toArray();
            //return response($data->pluck('Unmarked','mark'));
            foreach($lables as $label) {                                                  
                $ld = array_filter($data, function ($d) use ($label) {
                    //echo $label;
                    return $d->label == $label->label && $d->mark == 'Leader';
                });
                $rd = array_filter($data, function ($d) use ($label) {
                    //echo $label;
                    return $d->label == $label->label && $d->mark == 'Me';
                });
                $tt = 0;
                foreach($opponents as $op) {
                    $da = array_filter($data, function ($d) use ($label, $op) {
                        //echo $label;
                        return $d->label == $label->label && $d->mark == $op->alias;
                    });
                    $t = 0;
                    if($da != false){
                        array_push($dataset['opponent'][$op->alias], reset($da)->total); 
                        $t = reset($da)->total;
                    }
                    else {
                        array_push($dataset['opponent'][$op->alias], 0);
                    }
                    $tt = $tt + $t;
                }

                $led = array_filter($data, function ($d) use ($label) {
                    //echo $label;
                    return $d->label == $label->label && $d->mark == 'Leader';
                });
                $ud = array_filter($data, function ($d) use ($label) {
                    //echo $label;
                    return $d->label == $label->label && $d->mark == 'Undecided';
                });
                $nd = array_filter($data, function ($d) use ($label) {
                    //echo $label;
                    return $d->label == $label->label && $d->mark == 'Unmarked';
                });

                if($ld != false) array_push($dataset['leader'], reset($ld)->total);
                else array_push($dataset['leader'], 0);
                if($rd != false)  array_push($dataset['right'], reset($rd)->total);
                else array_push($dataset['right'], 0);
                if($ud != false) array_push($dataset['undecided'], reset($ud)->total);
                else array_push($dataset['undecided'], 0);
                if($nd != false) array_push($dataset['unmarked'], reset($nd)->total);
                else array_push($dataset['unmarked'], 0);

                array_push($dataset['left'], $tt);
            }
        }

        if($city !== 'all' && $municipality !== 'all' && $barangay !== 'all' && $purok !== 'all'){
            $lables = Voters::select('house_number as label')
            ->where('city', '=', $city)
            ->where('municipality', '=', $municipality)
            ->where('barangay', '=', $barangay)
            ->where('purok', '=', $purok)
            ->distinct()->get();

            $data = DB::table('voters')->rightJoin('marks','marks.voters_id','voters.id')
            ->selectRaw('voters.house_number as label, count(voters.id) as total, CASE WHEN marks.`mark` = "" THEN "Unmarked" ELSE marks.`mark` END AS mark')
            ->where('voters.city','=',$city)
            ->where('voters.municipality','=', $municipality)
            ->where('voters.barangay','=', $barangay)
            ->where('voters.purok','=', $purok)
            ->groupBy(['voters.house_number','marks.mark'])->get()->toArray();
            //return response($data->pluck('Unmarked','mark'));
            foreach($lables as $label) {                                                  
                $ld = array_filter($data, function ($d) use ($label) {
                    //echo $label;
                    return $d->label == $label->label && $d->mark == 'Leader';
                });
                $rd = array_filter($data, function ($d) use ($label) {
                    //echo $label;
                    return $d->label == $label->label && $d->mark == 'Me';
                });
                $tt = 0;
                foreach($opponents as $op) {
                    $da = array_filter($data, function ($d) use ($label, $op) {
                        //echo $label;
                        return $d->label == $label->label && $d->mark == $op->alias;
                    });
                    $t = 0;
                    if($da != false){
                        array_push($dataset['opponent'][$op->alias], reset($da)->total); 
                        $t = reset($da)->total;
                    }
                    else {
                        array_push($dataset['opponent'][$op->alias], 0);
                    }
                    $tt = $tt + $t;
                }

                $led = array_filter($data, function ($d) use ($label) {
                    //echo $label;
                    return $d->label == $label->label && $d->mark == 'Leader';
                });
                $ud = array_filter($data, function ($d) use ($label) {
                    //echo $label;
                    return $d->label == $label->label && $d->mark == 'Undecided';
                });
                $nd = array_filter($data, function ($d) use ($label) {
                    //echo $label;
                    return $d->label == $label->label && $d->mark == 'Unmarked';
                });

                if($ld != false) array_push($dataset['leader'], reset($ld)->total);
                else array_push($dataset['leader'], 0);
                if($rd != false)  array_push($dataset['right'], reset($rd)->total);
                else array_push($dataset['right'], 0);
                if($ud != false) array_push($dataset['undecided'], reset($ud)->total);
                else array_push($dataset['undecided'], 0);
                if($nd != false) array_push($dataset['unmarked'], reset($nd)->total);
                else array_push($dataset['unmarked'], 0);

                array_push($dataset['left'], $tt);
            }
            
        }

        $total = [
            "leader" => array_sum($dataset['leader']),
            "right" => array_sum($dataset['right']) + array_sum($dataset['leader']),
            "left" => array_sum($dataset['left']),
            "undecided" => array_sum($dataset['undecided']),
            "unmarked" => array_sum($dataset['unmarked']),
            "opponent" => []
        ];

        foreach($opponents as $op) {
            $a = [];
            $a['name'] = $op->alias;
            $a['color'] = $op->color;
            $a['total'] = array_sum($dataset['opponent'][$op->alias]);
            array_push($total['opponent'], $a);
        }

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
            "leader"=> DB::table('voters')->leftJoin('marks','marks.voters_id','voters.id')->where('marks.mark', '=', 'Leader')->count(),
            "right"=> DB::table('voters')->leftJoin('marks','marks.voters_id','voters.id')->where('marks.mark', '=', 'Right')->count(),
            "left"=> DB::table('voters')->leftJoin('marks','marks.voters_id','voters.id')->where('marks.mark', '=', 'Left')->count(),
            "undecided"=> DB::table('voters')->leftJoin('marks','marks.voters_id','voters.id')->where('marks.mark', '=', 'Undecided')->count(),
            "unmarked"=> DB::table('voters')->leftJoin('marks','marks.voters_id','voters.id')->where('marks.mark', '=', '')->count()
        ];
        return response()->json(["total" => $total]);
    }
    public function getbarcharttotal2() {

        $userid = Auth::user()->id;
        $surveyid = Survey::where('user_id','=',$userid)->where('isuse', '=', true)->first()->id;
        $opponents = Opponent::where('user_id','=',$userid)->get();

        $voters = DB::table('marks')
        ->selectRaw('marks.mark, count(*) as total')
        ->where('survey_id','=',$surveyid)
        ->groupBy('marks.mark')->orderBy('marks.mark')->get()->pluck("total", "mark");
        //return response()->json($voters);

        $total = [
            "voters"=> 0,
            "leader"=> 0,
            "right"=> 0,
            "left"=> 0,
            "undecided"=> 0,
            "unmarked"=> 0
        ];
        if(isset($voters["Leader"])) $total["leader"] = $voters["Leader"];
        if(isset($voters["Me"])) $total["right"] = $voters["Me"];
        if(isset($voters["Undecided"])) $total["undecided"] = $voters["Undecided"];
        if(isset($voters[""])) $total["unmarked"] = $voters[""];

        $tl = 0;
        foreach($opponents as $op) {
            $total[$op->alias] = 0;
            if(isset($voters[$op->alias])){
                $tl = $tl + $voters[$op->alias];
                $total[$op->alias] = $voters[$op->alias];
            }
            $total["left"] = $tl;
        }

        $total["voters"] = $total["leader"] + $total["right"] + $total["left"] + $total["undecided"] + $total["unmarked"];
        
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
