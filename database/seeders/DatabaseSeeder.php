<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        
        // $h1 = \App\Models\House::create(["house_number" => 1])->id;
        // $h2 = \App\Models\House::create(["house_number" => 2])->id;
        // $h3 = \App\Models\House::create(["house_number" => 3])->id;
        // $h4 = \App\Models\House::create(["house_number" => 4])->id;
        // $h5 = \App\Models\House::create(["house_number" => 5])->id;
        // $h6 = \App\Models\House::create(["house_number" => 6])->id;
        // $h7 = \App\Models\House::create(["house_number" => 7])->id;
        // $h8 = \App\Models\House::create(["house_number" => 8])->id;
        // $h9 = \App\Models\House::create(["house_number" => 9])->id;
        // $h10 = \App\Models\House::create(["house_number" => 10])->id;

        // $v1 = \App\Models\Voters::factory(10)->create(["house_id" => $h1]);
        // $v2 = \App\Models\Voters::factory(2)->create(["house_id" => $h2]);
        // $v3 = \App\Models\Voters::factory(5)->create(["house_id" => $h3]);
        // $v4 = \App\Models\Voters::factory(1)->create(["house_id" => $h4]);
        // $v5 = \App\Models\Voters::factory(6)->create(["house_id" => $h5]);
        // $v5 = \App\Models\Voters::factory(4)->create(["house_id" => $h6]);
        // $v5 = \App\Models\Voters::factory(7)->create(["house_id" => $h7]);
        // $v5 = \App\Models\Voters::factory(3)->create(["house_id" => $h8]);
        // $v5 = \App\Models\Voters::factory(4)->create(["house_id" => $h9]);
        // $v5 = \App\Models\Voters::factory(6)->create(["house_id" => $h10]);

        $city = ["Sorsogon"];
        $municipality = ["Sorsogon City", "Irosin", "Magallanes", "Gubat", "Juban", "Casiguran", "Bulan"];

        for ($a=0; $a < count($municipality); $a++) { 
            $m = $municipality[$a];
            $bn = rand(5, 15);
            for ($b=1; $b < $bn; $b++) { 
                $pn = rand(5, 10);
                for ($c=1; $c < $pn; $c++) {
                    $hn = rand(5, 20); 
                    for ($d=1; $d < $hn; $d++) { 
                        $house = \App\Models\House::create(["house_number" => $d])->id;
                        $vn = rand(1, 7);
                        $marks = ["Right", "Left", "Undecided"];
                        \App\Models\Voters::factory($vn)->create([
                            'house_id' => $house,
                            'house_number' => $d,
                            'purok' => $c,
                            'barangay' => 'Brgy ' . $b,
                            'municipality' => $m,
                            'mark' => $marks[array_rand($marks, 1)]
                        ]);
                    }
                }
            }
        }

        


        // \App\Models\Voter::class->crea([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        \App\Models\City::create(["name" => "Sorsogon"]);
        \App\Models\Municipality::create(["name" => "Irosin"]);
        \App\Models\Barangay::create(["name" => "Brgy 1", "purok" => 10]);
        \App\Models\Barangay::create(["name" => "Brgy 2", "purok" => 5]);
        \App\Models\Barangay::create(["name" => "Brgy 3", "purok" => 9]);
        \App\Models\Barangay::create(["name" => "Brgy 4", "purok" => 12]);
    }
}
