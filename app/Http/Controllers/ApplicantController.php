<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicantController extends Controller
{
    public function store(Request $request, Job $job)
    {

        $existingApplication = Applicant::where('job_id', $job->id)->where("user_id", Auth::user()->id)->exists();
        if($existingApplication){
            return back()->with('error',"You have already applied for this job");
        }

        $validatedData = $request->validate([
            'full_name' => 'required|string|min:2',
            'contact_phone' => 'string',
            'contact_email' => 'required|string|email',
            'message' => 'string',
            'location' => 'string',
            'resume' => 'required|file|mimes:pdf|max:2048'
        ]);
        if ($request->hasFile('resume')) {
            $path = $request->file('resume')->store('resumes', 'public');
            $validatedData['resume_path'] = $path;
        }

        $jobId = $job->id;
        $userId = Auth::user()->id;
        $validatedData['job_id'] = $jobId;
        $validatedData['user_id'] = $userId;

        Applicant::create($validatedData);
        // $application = new Applicant()
        return back()->with('success', 'Your Application has been submitted');
    }
    public function destroy(Applicant $applicant)
    {
        $applicant->delete();
        return back()->with('success', 'Applicant Deleted Successfully');
    }
}
