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
            
            if(City::where('name', '=', $ad['city'])->count() == 0){
                $city = City::create(["name" => $ad['city'], "region" => "V"]);
            }
            if(Municipality::where('name', '=', $ad['municipality'])->count() == 0){
                $mun = Municipality::create(["c_id" => $city->id, "name" => $ad['municipality']]);
            }
            if(Barangay::where('name', '=', $ad['brgy'])->count() == 0){
                $purok = rand(5, 10);
                $brgy = Barangay::create(["m_id" => $mun->id, "name" => $ad['brgy'], "purok" => $purok]);
            }
            
            for ($c = 1; $c < $purok; $c++) {
                $hn = rand(5, 30);
                for ($d = 1; $d < $hn; $d++) {
                    $house = House::create([
                        "house_number" => $d,
                        "c_id" => $city->id,
                        "m_id" => $mun->id,
                        "b_id" => $brgy->id,
                        "p_id" => $c,
                    ])->id;
                    $vn = rand(1, 7);
                    $marks = ["Right", "Right", "Right", "Right", "Left", "Left", "Undecided", ""];
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
