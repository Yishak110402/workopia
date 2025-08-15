<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JobController extends Controller
{
   use AuthorizesRequests;


   public function index()
   {
      $title = "All Job Listings";
      $jobs = Job::latest()->paginate(9);
      return view("jobs.index", [
         "title" => $title,
         "jobs" => $jobs
      ]);
   }
   public function create()
   {
      $title = "Create Job Listing";
      return view("jobs.create", [
         "title" => $title
      ]);
   }
   public function show(Job $job)
   {
      return view("jobs.show", [
         "job" => $job
      ]);
   }

   public function edit(Job $job)
   {

      $this->authorize('update', $job);
      return view('jobs.edit', ['job' => $job]);
   }
   public function update(Request $request, Job $job)
   {
      $this->authorize('update', $job);

      $validatedData = $request->validate([
         "title" => 'required|string|max:255',
         'description' => 'required|string',
         'salary' => 'required|integer',
         'tags' => 'nullable|string',
         'job_type' => 'required|string',
         'remote' => 'required|boolean',
         'requirements' => 'nullable|string',
         'benefits' => 'nullable|string',
         'address' => 'nullable|string',
         'city' => 'required|string',
         'contact_email' => 'required|string',
         'contact_phone' => 'required|string',
         'company_name' => 'required|string',
         'company_description' => 'nullable|string',
         'company_logo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
         'company_website' => 'nullable|url'
      ]);
      //Check for image
      if ($request->hasFile("company_logo")) {
         //delete old logo
         Storage::delete('public/logo' . basename($job->company_logo));
         //store file and get path
         $path = $request->file("company_logo")->store('logos', 'public');
         //add path to db
         $validatedData["company_logo"] = $path;
      }
      $job->update($validatedData);

      return redirect(route('jobs.show', $job->id))->with('success', "Job Listing Updated Successfully");
   }

   public function store(Request $request)
   {
      // dd($request->file('company_logo'));
      $validatedData = $request->validate([
         "title" => 'required|string|max:255',
         'description' => 'required|string',
         'salary' => 'required|integer',
         'tags' => 'nullable|string',
         'job_type' => 'required|string',
         'remote' => 'required|boolean',
         'requirements' => 'nullable|string',
         'benefits' => 'nullable|string',
         'address' => 'nullable|string',
         'city' => 'required|string',
         'contact_email' => 'required|string',
         'contact_phone' => 'required|string',
         'company_name' => 'required|string',
         'company_description' => 'nullable|string',
         'company_logo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
         'company_website' => 'nullable|url'
      ]);

      //hardcoded user
      $validatedData['user_id'] = Auth::user()->id;
      //Check for image
      if ($request->hasFile("company_logo")) {
         //store file and get path
         $path = $request->file("company_logo")->store('logos', 'public');
         //add path to db
         $validatedData["company_logo"] = $path;
      }

      Job::create($validatedData);
      return redirect(route('jobs.index'))->with('success', 'Job Created Successfully');
   }
   public function destroy(Job $job, Request $request)
   {
      $this->authorize('delete', $job);
      if ($job->company_logo) {
         Storage::delete('public/logos' . basename($job->company_logo));
      }
      $job->delete();
      if ($request->query('from') == 'dashboard') {
         return redirect(route('dashboard'))->with('success', "Job Listing Deleted Successfully");
      }

      return redirect(route('jobs.index'))->with('success', "Job Listing Deleted Successfully");
   }
   public function search(Request $request)
   {
      $keywords = strtolower($request->input('keywords'));
      $location = strtolower($request->input('location'));

      $query = Job::query();

      if ($keywords) {
         $query->where(function ($q) use ($keywords) {
            $q->whereRaw('LOWER(title) like ?', ['%' . $keywords . '%'])
               ->orWhereRaw('LOWER(description) like ?', ['%' . $keywords . '%'])
               ->orWhereRaw('LOWER(tags) like ?', ['%' . $keywords . '%']);
         });
      }
      if ($location) {
         $query->where(function ($q) use ($location) {
            $q->whereRaw('LOWER(address) like ?', ['%' . $location . '%'])
               ->orWhereRaw('LOWER(city) like ?', ['%' . $location . '%']);
         });
      }
      $jobs = $query->paginate(12);
      return view('jobs.index',[
         "jobs"=>$jobs
      ]);
   }
}
