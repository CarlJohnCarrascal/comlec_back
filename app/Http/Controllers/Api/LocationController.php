<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Barangay;
use App\Models\City;
use App\Models\House;
use App\Models\Municipality;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function get_cities(Request $req){
        $region = $req->region;
        $cities = City::all()->where('region', '=', $region);
        return response()->json($cities);
    }
    public function get_municipalities(Request $req){
        $c_id = $req->id;
        $municipalities = Municipality::all()->where('c_id', '=', $c_id);
        return response()->json($municipalities);
    }
    public function get_barangays(Request $req){
        $m_id = $req->id;
        $barangays = Barangay::all()->where('m_id', '=', $m_id);
        return response()->json($barangays);
    }
    public function get_houses(Request $req){
        $cid = $req->city;
        $mid = $req->municipality;
        $bid = $req->barangay;
        $pid = $req->purok;
        $houses = House::all()->where('c_id','=', $cid)
        ->where('m_id','=', $mid)
        ->where('b_id','=', $bid)
        ->where('p_id','=', $pid);
        return response()->json($houses);
    }
}
