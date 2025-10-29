<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\Portfolio;
use Illuminate\Database\Seeder;

class CandidateSeeder extends Seeder
{
    public function run(): void
    {
        $candidates = [
            // President
            ['portfolio' => 'President', 'name' => 'Anjoy Narzary'],
            ['portfolio' => 'President', 'name' => 'Ring Fung Moni Basumatary'],

            // Vice President
            ['portfolio' => 'Vice President', 'name' => 'Ansula Brahma'],
            ['portfolio' => 'Vice President', 'name' => 'Karina Brahma'],

            // General Secretary
            ['portfolio' => 'General Secretary', 'name' => 'Issac Mohan Narzary'],
            ['portfolio' => 'General Secretary', 'name' => 'Swranggiri Basumatary'],

            // Assistant General Secretary
            ['portfolio' => 'Assistant General Secretary', 'name' => 'Roshmi Narzary'],

            // Debate & Symposium Secretary
            ['portfolio' => 'Debate & Symposium Secretary', 'name' => 'Krishna Bala Brahma'],
            ['portfolio' => 'Debate & Symposium Secretary', 'name' => 'Kasish Saha'],

            // Boys Common Room Secretary
            ['portfolio' => 'Boys Common Room Secretary', 'name' => 'Juel Minz'],
            ['portfolio' => 'Boys Common Room Secretary', 'name' => 'Swrangsar Brahma'],

            // Girls Common Room Secretary
            ['portfolio' => 'Girls Common Room Secretary', 'name' => 'Dhwnshri Brahma'],
            ['portfolio' => 'Girls Common Room Secretary', 'name' => 'Pami Uzir'],

            // Literary Activity Secretary
            ['portfolio' => 'Literary Activity Secretary', 'name' => 'Bikram Chandra Brahma'],
            ['portfolio' => 'Literary Activity Secretary', 'name' => 'Olivia Brahma'],

            // Social Science Secretary
            ['portfolio' => 'Social Science Secretary', 'name' => 'Raj Narayan Hazoary'],
            ['portfolio' => 'Social Science Secretary', 'name' => 'Mousumi Brahma'],

            // Minor Games & Sports Secretary
            ['portfolio' => 'Minor Games & Sports Secretary', 'name' => 'Uday Ranjan Das'],
            ['portfolio' => 'Minor Games & Sports Secretary', 'name' => 'Rwngwosat Brahma'],

            // Major Games & Sports Secretary
            ['portfolio' => 'Major Games & Sports Secretary', 'name' => 'Supriya Rani Narzary'],
            ['portfolio' => 'Major Games & Sports Secretary', 'name' => 'Rahul Brahma'],

            // Cultural Activity Secretary
            ['portfolio' => 'Cultural Activity Secretary', 'name' => 'Dhwnjuli Basumatary'],
            ['portfolio' => 'Cultural Activity Secretary', 'name' => 'Khansai Gwra Brahma'],
        ];

        foreach ($candidates as $candidate) {
            $portfolio = Portfolio::where('name', $candidate['portfolio'])->first();
            if ($portfolio) {
                Candidate::create([
                    'portfolio_id' => $portfolio->id,
                    'name' => $candidate['name'],
                    'position' => $portfolio->name,
                    'is_active' => true,
                ]);
            }
        }
    }
}
