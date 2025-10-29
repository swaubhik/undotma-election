<?php

namespace Database\Seeders;

use App\Models\Portfolio;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $portfolios = [
            ['name' => 'President'],
            ['name' => 'Vice President'],
            ['name' => 'General Secretary'],
            ['name' => 'Assistant General Secretary'],
            ['name' => 'Debate & Symposium Secretary'],
            ['name' => 'Boys Common Room Secretary'],
            ['name' => 'Girls Common Room Secretary'],
            ['name' => 'Literary Activity Secretary'],
            ['name' => 'Social Science Secretary'],
            ['name' => 'Minor Games & Sports Secretary'],
            ['name' => 'Major Games & Sports Secretary'],
            ['name' => 'Cultural Activity Secretary'],
        ];

        foreach ($portfolios as $portfolio) {
            Portfolio::create([
                'name' => $portfolio['name'],
                'slug' => Str::slug($portfolio['name']),
                'is_active' => true,
            ]);
        }
    }
}
