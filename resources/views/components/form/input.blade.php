@props(['label', 'type' => 'text', 'name', 'value' => '', 'placeholder' => ''])

<div>
    <label class="block text-gray-700 font-semibold">{{ $label }}</label>
    <input type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $value) }}"
           {{ $attributes->merge(['class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500']) }}
           placeholder="{{ $placeholder }}">
</div>
