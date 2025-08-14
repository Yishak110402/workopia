@props([
    'label' => null,
    'id',
    'name',
    'options' => [],
    'values' => [],
    'value' => '',
])

<div class="mb-4">
    @if ($label)
        <label class="block text-gray-700" for="job_type">{{ $label }}</label>
    @endif
    <select id="{{ $id }}" name="{{ $name }}" value="{{ old($name, $value) }}"
        class="w-full px-4 py-2 border rounded focus:outline-none @error($name) border-red-500 @enderror">
        @forelse($options as $option)
            <option value="{{ $values[$loop->iteration - 1] }}" {{ old($name, $value) == $values[$loop->iteration - 1] ? 'selected' : '' }}>
                {{ $option }}
            </option>
        @empty
            <option value="None">No Options</option>
        @endforelse
    </select>
    @error($name)
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
