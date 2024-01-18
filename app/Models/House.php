<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    use HasFactory;

    protected $appends = [
        'member',
        'head',
        'check'
    ];

    public function getMemberAttribute(){
        return Voters::where('house_id','=',$this->id)->count();
    }
    public function getHeadAttribute(){
        $head =  Voters::where('house_id','=',$this->id)->where('ishead','=',true)->first();
        if($head){
            return $head->fname . ' ' . $head->mname . ' ' . $head->lname;
        }else return "No Family Head";
    }
    public function getCheckAttribute(){
        return false;
    }
}
