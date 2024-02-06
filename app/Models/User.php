<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'color',
        'alias',
        'type',
        'corvered_area_country',
        'corvered_area_city',
        'corvered_area_municipality',
        'corvered_area_district',
        'corvered_area_barangay',
        'email',
        'password',
    ];

    protected $appends = [
        'address',
        'country',
        'city',
        'municipality',
        'barangay'
    ];

    public function getAddressAttribute($value)
    {
        $addr = "";
        if($this->attributes['corvered_area_barangay'] !== null) $addr = $addr . ', ' . Barangay::where('id', '=', $this->attributes['corvered_area_barangay'])->first()->name;
        if($this->attributes['corvered_area_municipality'] !== null) $addr = $addr . ', ' . Municipality::where('id', '=', $this->attributes['corvered_area_municipality'])->first()->name;
        if($this->attributes['corvered_area_city'] !== null) $addr = $addr . ', ' . City::where('id', '=', $this->attributes['corvered_area_city'])->first()->name;
        if($this->attributes['corvered_area_country'] !== null) $addr =  $addr . ', ' . $this->attributes['corvered_area_country'];
        if(substr($addr, 0, 1) == ",") $addr = substr($addr, 2);
        return $addr;
    }

    public function getCountryAttribute(){
        if($this->attributes['corvered_area_country'] !== null) return $this->attributes['corvered_area_country'];
    }
    public function getCityAttribute(){
        if($this->attributes['corvered_area_country'] !== null) return City::where('id', '=', $this->attributes['corvered_area_city'])->first();
    }
    public function getMunicipalityAttribute(){
        if($this->attributes['corvered_area_country'] !== null) return Municipality::where('id', '=', $this->attributes['corvered_area_municipality'])->first();
    }
    public function getBarangayAttribute(){
        if($this->attributes['corvered_area_country'] !== null) return Barangay::where('id', '=', $this->attributes['corvered_area_barangay'])->first();
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
