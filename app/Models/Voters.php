<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voters extends Model
{
    use HasFactory;

    protected $fillable = [
        'precint_number',
        'house_id',
        'house_number',
        'purok',
        'barangay',
        'municipality',
        'district',
        'city',
        'fname',
        'mname',
        'lname',
        'suffix',
        'gender',
        'birthdate',
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
