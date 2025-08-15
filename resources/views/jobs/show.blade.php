<x-layout>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <section class="md:col-span-3">
            <div class="rounded-lg shadow-md bg-white p-3">
                <div class="flex justify-between items-center">
                    <a class="block p-4 text-blue-700" href="{{ url('/jobs') }}">
                        <i class="fa fa-arrow-alt-circle-left"></i>
                        Back To Listings
                    </a>
                    {{-- @auth --}}
                    {{-- @if (auth()->user()->id == $job->user_id) --}}
                    @can('update', $job)
                        <div class="flex space-x-3 ml-4">
                            <a href="{{ route('jobs.edit', $job->id) }}"
                                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded">Edit</a>
                            <!-- Delete Form -->
                            <form method="POST" action="{{ route('jobs.destroy', $job->id) }}"
                                onsubmit="return confirm('Are you sure you want to delete the listing?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded">
                                    Delete
                                </button>
                            </form>
                            <!-- End Delete Form -->
                        </div>
                    @endcan
                    {{-- @endif
                    @endauth --}}
                </div>
                <div class="p-4">
                    <h2 class="text-xl font-semibold">
                        {{ $job->title }}
                    </h2>
                    <p class="text-gray-700 text-lg mt-2">
                        {{ $job->description }}
                    </p>
                    <ul class="my-4 bg-gray-100 p-4">
                        <li class="mb-2">
                            <strong>Job Type:</strong> {{ $job->job_type }}
                        </li>
                        <li class="mb-2">
                            <strong>Remote:</strong> {{ $job->remote ? 'Yes' : 'No' }}
                        </li>
                        <li class="mb-2">
                            <strong>Salary:</strong> ${{ number_format($job->salary) }}
                        </li>
                        <li class="mb-2">
                            <strong>Site Location:</strong> {{ $job->city }}
                        </li>
                        @if ($job->tags)
                            <li class="mb-2">
                                <strong>Tags:</strong> {{ ucwords(str_replace(',', ', ', $job->tags)) }}
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="container mx-auto p-4">
                @if ($job->requirements || $job->benefits)
                    <h2 class="text-xl font-semibold mb-4">Job Details</h2>
                    <div class="rounded-lg shadow-md bg-white p-4">
                        {{-- @if ($job->requirements) --}}
                        <h3 class="text-lg font-semibold mb-2 text-blue-500">
                            Job Requirements
                        </h3>
                        <p>
                            {{ ucwords(str_replace(',', ', ', $job->requirements)) }}
                        </p>
                        <h3 class="text-lg font-semibold mt-4 mb-2 text-blue-500">
                            Benefits
                        </h3>
                        <p>
                            {{ ucwords(str_replace(',', ', ', $job->benefits)) }}
                        </p>
                    </div>
                @endif

                @auth
                    <p class="my-5">
                        Put "Job Application" as the subject of your email
                        and attach your resume.
                    </p>
                    <div x-data="{ open: false }">
                        <button @click="open=true"
                            class="block w-full text-center px-5 py-2.5 shadow-sm rounded border text-base font-medium cursor-pointer text-indigo-700 bg-indigo-100 hover:bg-indigo-200">Apply
                            Now</button>
                        <div x-show="open"
                            class="fixed inset-0 flex items-center justify-center bg-gray-900/50 bg-opacity-50">
                            <div @click.away="open=false" class="bg-white p-6 rounded-lg shadow-md w-full max-w-md">
                                <h3 class="text-lg font-semibold mb-4">Apply For {{ $job->title }}</h3>
                                <form enctype="multipart/form-data" method="POST"
                                    action="{{ route('applicants.store', $job->id) }}">
                                    @csrf
                                    <x-inputs.text-input :required="true" name="full_name" id="full_name"
                                        label="Full Name" value="{{auth()->user()->name}}" :disabled="true" />
                                    <x-inputs.text-input :required="false" name="contact_phone" id="contact_phone"
                                        label="Contact Phone" />
                                    <x-inputs.text-input :required="true" name="contact_email" id="contact_email"
                                        label="Contact Email" value="{{auth()->user()->email}}" :disabled="true"/>
                                    <x-inputs.text-area-input id="message" label="Message" name="message" />
                                    <x-inputs.text-input :required="true" name="location" id="location"
                                        label="Location" />
                                    <x-inputs.file-input id='resume' name="resume" label="Upload your resume(PDF)"
                                        :required="true" />
                                    <button type="submit"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">Apply</button>
                                    <button @click="open=false"
                                        class="bg-gray-500 hover:bg-gray-600 text-black px-4 py-2 rounded-md">Cancel</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <p class="my-5 bg-gray-300 rounded p-3">
                        You must be logged in to apply for the job
                    </p>
                @endauth

            </div>

            <div class="bg-white p-6 rounded-lg shadow-md mt-6">
                <div id="map"></div>
            </div>
        </section>
        <aside class="bg-white rounded-lg shadow-md p-3">
            <h3 class="text-xl text-center mb-4 font-bold">
                Company Info
            </h3>
            @if ($job->company_logo)
                <img src="/storage/{{ $job->company_logo }}" alt="Ad" class="w-full rounded-lg mb-4 m-auto" />
            @endif
            <h4 class="text-lg font-bold">{{ $job->company_name }}</h4>
            @if ($job->company_description)
                <p class="text-gray-700 text-lg my-3">
                    {{ $job->company_description }}
                </p>
            @endif
            @if ($job->company_website)
                <a href="{{ $job->company_website }}" target="_blank" class="text-blue-500">Visit Website</a>
            @endif
            @auth
                <form
                    action="{{ auth()->user()->bookmarkedJobs()->where('job_id', $job->id)->exists() ? route('bookmarks.destroy', $job->id) : route('bookmarks.store', $job->id) }}"
                    method="POST">
                    @csrf
                    @if (auth()->user()->bookmarkedJobs()->where('job_id', $job->id)->exists())
                        @method('DELETE')
                        <button
                            class="bg-red-500 hover:bg-red-600 text-white font-bold w-full py-2 px-4 rounded-full flex items-center justify-center mt-5"
                            type='submit'>
                            <i class="fas fa-bookmark mr-3"></i> Remove Bookmark
                        </button>
                    @else
                        <button
                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold w-full py-2 px-4 rounded-full flex items-center justify-center mt-5"
                            type='submit'>
                            <i class="fas fa-bookmark mr-3"></i> Bookmark Listing
                        </button>
                    @endif
                </form>
            @else
                <p class="mt-10 bg-gray-200 text-gray-700 w-full py-2 px-4 rounded-full text-center">
                    <i class="fa fa-info-circle mr-3"></i>You must be logged in to bookmark
                </p>
            @endauth

        </aside>
    </div>
</x-layout>
