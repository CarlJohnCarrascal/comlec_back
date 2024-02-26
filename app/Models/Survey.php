<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'isuse',
        'status'
    ];

    protected $appends = [
        'total'
    ];


    public function getStatusAttribute() {
        $t = DB::table('voters')->count();
        $m = DB::table('marks')->where('survey_id','=', $this->id)->where('mark','!=','')->count();
        $p = (100 / $t) * $m;
        return number_format((float)$p, 2, '.', '') . "% complete";
    }

    public function getUpdatedAtAttribute() {
        $t = DB::table('voters')->count();
        $m = DB::table('marks')->where('survey_id','=', $this->id)->orderBy('updated_at', 'desc')->limit(1)->first();
        if($m == null) return "";
        else return $m->updated_at;
    }

    public function getTotalAttribute() {

        $user =  Auth::user();
        $surveyid = $this->id;

        $marks = DB::table('voters')->leftJoin('marks', function ($join) use ($surveyid) {
            $join->on('voters.id', 'marks.voters_id')
            ->where('marks.survey_id', '=', $surveyid);
        })
        ->selectRaw('count(voters.id) as total, CASE WHEN marks.`mark` = "" OR ISNULL(marks.mark) THEN "Unmarked" ELSE marks.`mark` END AS mark');

        switch ($user->type) {
            case 'city':
                $city = City::where('id','=',$user['corvered_area_city'])->first()->name;
                $totalVoters = Voters::where('city', '=', $city)->count();

                $marks->where('voters.city', '=', $city)
                ->groupBy('marks.mark')->orderBy('marks.mark');

                break;
            case 'municipality':
                $city = City::where('id','=',$user['corvered_area_city'])->first()->name;
                $municipality = Municipality::where('id','=',$user['corvered_area_municipality'])->first()->name;
                $totalVoters = Voters::where('city', '=', $city)
                ->where('municipality','=', $municipality)->count();

                $marks->where('voters.city', '=', $city)
                ->where('voters.municipality','=', $municipality)
                ->groupBy('marks.mark')->orderBy('marks.mark');

                break;
            case 'barangay':
                $city = City::where('id','=',$user['corvered_area_city'])->first()->name;
                $municipality = Municipality::where('id','=',$user['corvered_area_municipality'])->first()->name;
                $barangay = Barangay::where('id','=',$user['corvered_area_barangay'])->first()->name;
                $totalVoters = Voters::where('city', '=', $city)
                ->where('municipality','=', $municipality)
                ->where('barangay','=', $barangay)->count();

                
                $marks->where('voters.city', '=', $city)
                ->where('voters.municipality','=', $municipality)
                ->where('voters.barangay','=', $barangay)
                ->groupBy('marks.mark')->orderBy('marks.mark');
                
                //return response($marks);
                break;   
        }

        $marks = $marks->get()->toArray();

        $totalVoters = array_sum(array_column($marks, 'total'));
        $unmarked = array_filter($marks, function ($d) {
            return $d->mark == 'Unmarked';
        });
        $totalMarked = array_sum(array_column($marks, 'total')) - array_sum(array_column($unmarked, 'total'));
        $complete = (100 / $totalVoters) * $totalMarked;
        return [
            'total_voters' => $totalVoters,
            'total_marked' => $totalMarked,
            'complete' => number_format((float)$complete, 2, '.', '') . "% marked"
        ];
    }
}
