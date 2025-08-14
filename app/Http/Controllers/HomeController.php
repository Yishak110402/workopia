<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;

class HomeController extends Controller
{
    public function index(){
        $jobs = Job::latest()->limit(3)->get();

        return view("pages.index", [
            'jobs'=> $jobs
        ]);
    }
}
