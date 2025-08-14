<x-layout>
    <x-slot name="title">Edit Job</x-slot>
    <div class="bg-white mx-auto p-8 rounded-lg shadow-md w-full md:max-w-3xl">
        <h2 class="text-4xl text-center font-bold mb-4">
            Edit Job Listing
        </h2>
        <form method="POST" action="{{route('jobs.update',$job->id)}}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-500">
                Job Info
            </h2>
            <x-inputs.text-input id="title" name="title" label="Job Title" placeholder="Software Engineer" :value="old('title', $job->title)"/>
            <x-inputs.text-area-input id="description" name="description" label="Job Description" :value="old('description', $job->description)"/>
            <x-inputs.text-input id="salary" name="salary" label="Annual Salary" placeholder="90000"
                type="number" :value="old('salary', $job->salary)"/>
            <x-inputs.text-area-input id="requirements" name="requirements" label="Requirements" :value="old('requirements', $job->requirements)"/>
            <x-inputs.text-area-input id="benefits" name="benefits" label="Benefits" :value="old('benefits', $job->benefits)"/>
            <x-inputs.text-input id="tags" name="tags" label="Tags (comma-separated)"
                placeholder="development,java,coding,python" type="text" :value="old('tags', $job->tags)"/>

            <x-select-input id="job_type" name="job_type" label="Job Type" :options="['Full-Time', 'Part-Time', 'Contract', 'Temporary', 'Internship', 'Volunteer', 'On-Call']" :values="['Full-Time', 'Part-Time', 'Contract', 'Temporary', 'Internship', 'Volunteer', 'On-Call']" :value="old('job_type', $job->job_type)"/>
            <x-select-input id="remote" name="remote" label="Remote" :options="['Yes', 'No']" :values="[1, 0]" :value="old('remote', $job->remote)"/>
            <x-inputs.text-input id="address" name="address" label="Address" placeholder="123 Main St"
                type="text" :value="old('address', $job->address)"/>
            <x-inputs.text-input id="city" name="city" label="City" placeholder="Addis Ababa"
                type="text" :value="old('city', $job->city)" />
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-500">
                Company Info
            </h2>
            <x-inputs.text-input id="company_name" name="company_name" label="Company Name" placeholder="Company name"
                type="text" :value="old('company_name', $job->company_name)"/>
            <x-inputs.text-area-input id="company_description" name="company_description" label="Company Description" :value="old('company_description', $job->company_description)"/>
            <x-inputs.text-input id="company_website" name="company_website" label="Company Website"
                placeholder="Enter Website" type="url" :value="old('company_website', $job->company_website)" />
            <x-inputs.text-input id="contact_phone" name="contact_phone" label="Contact Phone" placeholder="Enter Phone"
                type="text" :value="old('contact_phone', $job->contact_phone)"/>
            <x-inputs.text-input id="contact_email" name="contact_email" label="Contact Email" placeholder="Enter Email"
                type="email" :value="old('contact_email', $job->contact_email)"/>
            <x-inputs.file-input id="company_logo" name="company_logo" label="Company Logo" :value="old('company_logo', $job->company_logo)"/>
            <button type="submit"
                class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 my-3 rounded focus:outline-none">
                Save
            </button>
        </form>
    </div>
    <script>
        
    </script>
</x-layout>
