@props(['label' => '', 'name', 'checked' => false])

<div class="flex items-center">
    <input type="checkbox" name="{{ $name }}" id="{{ $id ?? $name }}" {{ $checked ? 'checked' : '' }}
        {{ $attributes->merge(['class' => 'mr-2 rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500']) }}>
    @if($label)
        <label for="{{ $id ?? $name }}" class="text-gray-700 font-semibold">{{ $label }}</label>
    @endif
</div>
