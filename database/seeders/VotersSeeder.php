<?php

namespace Database\Seeders;

use App\Models\Barangay;
use App\Models\City;
use App\Models\Config;
use App\Models\House;
use App\Models\Mark;
use App\Models\Municipality;
use App\Models\Opponent;
use App\Models\Survey;
use App\Models\User;
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

        $user = User::create([
            'name' => 'John Doe',
            'alias' => 'Me',
            'type' => 'city',
            'email' => 'johndoe@mail.com',
            'password' => bcrypt('12345'),
            'color' => '#05b828',
            'corvered_area_country' => 'Philippines',
            'corvered_area_city' => 1
        ]);

        Config::create([
            'user_id' => $user->id,
        ]);

        $survey = Survey::create([
            'user_id' => $user->id,
            'name' => 'my first survey',
            'isuse' => true
        ]);

        Opponent::create([
            'user_id' => $user->id,
            'name' => 'Christopher Jennas',
            'alias' => 'CJ',
            'color' => '#fffb00',
        ]);
        Opponent::create([
            'user_id' => $user->id,
            'name' => 'Oves Faneru',
            'alias' => 'OF',
            'color' => '#9a10de',
        ]);


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
                    $marks = ["Me", "Me", "Me", "Me", "OF", "CJ", "OF", "CJ", "Undecided", ""];
                    $m = $marks[array_rand($marks, 1)];
                    $lname = fake()->lastName();
                    $mname = fake()->lastName();

                    for ($gg = 1; $gg < $vn; $gg++) {
                        $gender = ["male", "female"];
                        $g = $gender[array_rand($gender, 1)];
                        $h = false;
                        if($gg == 1) $h = true;

                        $voters = Voters::create([
                            'house_id' => $house,
                            'house_number' => $d,
                            'purok' => $c,
                            'barangay' => $ad['brgy'],
                            'municipality' => $ad['municipality'],
                            'city' => $ad['city'],
                            'lname' => $lname,
                            'mname' => $mname,
                            'fname' => fake()->firstName($g),
                            'suffix' => '',
                            'birthdate' => fake()->date('Y-m-d', '2005-01-01'),
                            'gender' => $g,
                            'status' => '',
                            'ishead' => $h
                        ]);
                        Mark::create([
                            'survey_id' => $survey->id,
                            'voters_id' => $voters->id,
                            'mark' => $m
                        ]);
                    }
                }
            }
        }
    }
}
