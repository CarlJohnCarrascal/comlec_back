<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
}
