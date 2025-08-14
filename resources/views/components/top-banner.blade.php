@props([
    "heading"=>"heading",
    "subHeading"=>"subHeading"
])

<section class="bg-blue-900 text-white py-6 text-center">
    <div class="container mx-auto">
        <h2 class="text-3xl font-semibold">
            {{$heading}}
        </h2>
        <p class="text-lg mt-2">
            {{$subHeading}}
        </p>
    </div>
</section>