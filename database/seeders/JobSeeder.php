<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Load Job Listings from file
        $jobListings = include database_path('seeders/data/job_listings.php');

        //Get Test user id
        $testUser = User::where('email', 'yohannes@gmail.com')->value('id');


        //Get user ids from the user model
        $userIds = User::where('email', '!=', 'yohannes@gmail.com')->pluck('id')->toArray();
        foreach ($jobListings as $index => &$listing) {
            if ($index < 2) {
                $listing["user_id"] = $testUser;
            } else {
                $listing['user_id'] = $userIds[array_rand(($userIds))];
            }
            //add timestamps
            $listing['created_at'] = now();
            $listing['updated_at'] = now();
        }
        Db::table('job_listings')->insert($jobListings);
        echo "Jobs created successfully";
    }
}
