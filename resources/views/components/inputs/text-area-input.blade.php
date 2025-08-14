@props([
    'label'=>null,
    'id',
    'name',
    'value'=>""
])

<div class="mb-4">
    @if($label)
    <label class="block text-gray-700" for="description">{{$label}}</label>
    @endif
    <textarea cols="30" rows="7" id="{{$id}}" name="{{$name}}"
        class="w-full px-4 py-2 border rounded focus:outline-none @error($name) border-red-500 @enderror"
        placeholder="">{{ old($name, $value) }}</textarea>
    @error($name)
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>