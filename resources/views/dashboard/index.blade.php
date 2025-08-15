<x-layout>
    <section class="flex flex-col gap-4 md:flex-row">
        <div class="bg-white p-8 rounded-lg shadow-md w-full">
            <h3 class="text-3xl text-center font-bold mb-4">Profile Info</h3>
            @if ($user->avatar)
                <div class="mt-2 flex-justify-center">
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{$user->name}} profile"
                        class="w-32 h-32 object-cover rounded-full">
                </div>
            @endif
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <x-inputs.text-input id='name' name='name' label="Name" value="{{ $user->name }}" />
                <x-inputs.text-input id='email' name='email' label="Email" value="{{ $user->email }}"
                    type='email' />
                <x-inputs.file-input id="avatar" name="avatar" label="Avatar" />
                <button class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2" type="submit">Save</button>
            </form>
        </div>
        <div class="bg-white p-8 rounded-lg shadow-md w-full">
            <h3 class="text-3xl text-center font-bold mb-4">My Job Listings</h3>
            @forelse($jobs as $job)
                <div class="flex justify-between items-center border-b-2 border-gray-200 py-2">
                    <div>
                        <h3 class="text-xl font-semibold">{{ $job->title }}</h3>
                        <p class="text-gray-700">{{ $job->job_type }}</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('jobs.edit', $job->id) }}"
                            class="bg-blue-500 text-white px-4 py-2 rounded text-sm">Edit</a>
                        <form method="POST" action="{{ route('jobs.destroy', $job->id) }}?from=dashboard"
                            onsubmit="return confirm('Are you sure you want to delete the listing?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded text-sm">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
                {{-- Applicatns --}}
                <div class="mt-4">
                    <h4 class="text-lg font--semibold mb-2">Applicants</h4>
                    @forelse ($job->applicants as $applicant)
                        <div class="py-2">
                            <p class="text-gray-800">
                                <strong>Name: </strong>{{$applicant->full_name}}
                            </p>
                            <p class="text-gray-800">
                                <strong>Phone Number: </strong>{{$applicant->contact_phone}}
                            </p>
                            <p class="text-gray-800">
                                <strong>Email: </strong>{{$applicant->email}}
                            </p>
                            <p class="text-gray-800">
                                <strong>Message: </strong>{{$applicant->message}}
                            </p>
                            <p class="text-gray-800 my-1">
                                <a href="{{asset('storage/'.$applicant->resume_path)}}" class="text-blue-500 hover:underline" download><i class="fas fa-download"></i> Download Resume</a>
                            </p>
                            <form action="{{route('applicants.destroy',$applicant->id)}}" method="POST" onsubmit="return confirm('Are you sure you want to delete thsi applicant?')">
                                @csrf
                                @method("DELETE")
                                <button class="text-red-500 text-sm hover:text-red-700" type="submit">
                                    <i class="fas fa-trash"></i> Delete Applicant
                                </button>
                            </form>
                        </div>
                    @empty
                        <p class="text-gray-700 mb-5">No Applicants for this job</p>
                    @endforelse
                </div>
            @empty
                <p class="text-gray-700">You have no Job Listings</p>
            @endforelse

        </div>
    </section>
    <x-bottom-banner />
</x-layout>
