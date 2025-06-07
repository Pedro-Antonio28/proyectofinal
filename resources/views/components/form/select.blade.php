@props(['label', 'name', 'options' => [], 'selected' => ''])

<div>
    <label class="block text-gray-700 font-semibold">{{ $label }}</label>
    <select name="{{ $name }}"
        {{ $attributes->merge(['class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500']) }}>
        @foreach($options as $value => $text)
            <option value="{{ $value }}" {{ $value == old($name, $selected) ? 'selected' : '' }}>{{ $text }}</option>
        @endforeach
    </select>
</div>
