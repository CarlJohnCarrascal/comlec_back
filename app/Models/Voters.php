<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voters extends Model
{
    use HasFactory;

    protected $fillable = [
        'house_id',
        'purok',
        'barangay',
        'municipality',
        'city',
        'fname',
        'mname',
        'lname',
        'suffix',
        'gender',
        'birthdate',
        'mark',
        'status',
        'ishead'
    ];

    protected $appends = [
        'check'
    ];

    public function getCheckAttribute(){
        return false;
    }
}
