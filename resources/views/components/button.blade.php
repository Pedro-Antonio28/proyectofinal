<button {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-white hover:bg-green-700 transition']) }}>
    {{ $slot }}
</button>
