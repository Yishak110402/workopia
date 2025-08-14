@props(['url' => '/', 'icon' => null, 'bgColor' => 'yellow-500', 'hoverClass' => 'yellow-600', 'textColor' => 'black', "block"=> false])


<a href={{ url($url) }}
    class="{{$block ? "block" : ""}} bg-{{ $bgColor }} hover:bg-{{ $hoverClass }} text-{{ $textColor }} px-4 py-2 rounded hover:shadow-md transition duration-300">
    @if($icon)
        <i class="fa fa-{{$icon}}"></i>
    @endif
    {{ $slot }}
</a>