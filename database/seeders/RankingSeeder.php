<?php

namespace Database\Seeders;

use App\Models\Ranking;
use Illuminate\Database\Seeder;

class RankingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $list_ranking = ['Bronze', 'Silver', 'Gold', 'Platinum', 'Diamond'];

        for ($i = 0; $i < count($list_ranking); $i++) {
            $ranking_name = $list_ranking[$i];

            $check_point = 0;
            switch ($ranking_name) {
                case 'Bronze':
                    $check_point = 0;
                    break;
                case 'Silver':
                    $check_point = 1000;
                    break;
                case 'Gold':
                    $check_point = 2500;
                    break;
                case 'Platinum':
                    $check_point = 5000;
                    break;
                case 'Diamond':
                    $check_point = 10000;
                    break;
                default:
                    # code...
                    break;
            }

            Ranking::factory()->create([
                'ranking_name' => $ranking_name,
                'check_point' => $check_point,
            ]);
        }
    }
}
