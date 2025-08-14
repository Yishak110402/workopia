<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookmarkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Get test user
        $testUser = User::where('email', 'yohannes@gmail.com')->firstOrFail();
        //Get All JobIds
        $jobIds = Job::pluck('id')->toArray();
        $randomJobIds = array_rand($jobIds, 3);
        //attach the selected jobs as bookmarks
        foreach($randomJobIds as $ids){
            $testUser->bookmarkedJobs()->attach($jobIds[$ids]);
        }
        
    }
}
