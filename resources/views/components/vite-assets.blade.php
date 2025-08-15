@if (file_exists(public_path('build/manifest.json')))
    @php
        $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
    @endphp

    {{-- CSS Assets --}}
    @if (isset($manifest['resources/css/app.css']['file']))
        <link rel="stylesheet" href="{{ asset('build/' . $manifest['resources/css/app.css']['file']) }}">
    @endif

    {{-- JavaScript Assets --}}
    @if (isset($manifest['resources/js/app.js']['file']))
        <script src="{{ asset('build/' . $manifest['resources/js/app.js']['file']) }}" defer></script>
    @endif
@endif
