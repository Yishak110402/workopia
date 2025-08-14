<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        $user = Auth::user();
        $jobs = Job::where('user_id',$user->id)->get();
        return view('dashboard.index',[
            'user'=>$user,
            'jobs'=>$jobs
        ]);
    }
}
