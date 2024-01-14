<?php

namespace Database\Seeders;

use App\Models\Barangay;
use App\Models\City;
use App\Models\House;
use App\Models\Municipality;
use App\Models\Voters;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class VotersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = Storage::disk('local')->get('/json/ads.json');
        $ads = json_decode($json, true);

        $city = "";
        $mun = "";
        $brgy = "";
        $purok = "";
        $purok2 = "";
        foreach ($ads as $ad) {
            
            if(City::all()->where('name', '=', $ad['city'])){
                $city = City::create(["name" => $ad['city'], "region" => "V"]);
            }
            if(Municipality::all()->where('name', '=', $ad['municipality'])){
                $mun = Municipality::create(["c_id" => $city->id, "name" => $ad['municipality']]);
            }
            if(Barangay::all()->where('name', '=', $ad['brgy'])){
                $purok = rand(5, 10);
                $purok2 = Barangay::create(["m_id" => $city->id, "name" => $ad['brgy'], "purok" => $purok]);
            }
            
            for ($c = 1; $c < $purok; $c++) {
                $hn = rand(5, 20);
                for ($d = 1; $d < $hn; $d++) {
                    $house = House::create(["house_number" => $d])->id;
                    $vn = rand(1, 7);
                    $marks = ["Right", "Left", "Undecided", ""];
                    Voters::factory($vn)->create([
                        'house_id' => $house,
                        'house_number' => $d,
                        'purok' => $c,
                        'barangay' => $ad['brgy'],
                        'municipality' => $ad['municipality'],
                        'city' => $ad['city'],
                        'mark' => $marks[array_rand($marks, 1)]
                    ]);
                }
            }
        }
    }
}
