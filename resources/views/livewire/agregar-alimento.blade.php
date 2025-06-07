<div class="flex justify-center items-center min-h-screen bg-[#f8fff4]">
    <div class="bg-white shadow-xl rounded-2xl p-8 border border-gray-300 w-full max-w-md">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">{{ __('messages.add_food') }}</h2>

        <div class="space-y-4">
            {{-- Componente input para nombre --}}
            <x-form.input label="{{ __('messages.food_name') }}" name="nombre" placeholder="Ej: Pollo a la plancha" />

            {{-- Componente input para calorías --}}
            <x-form.input label="{{ __('messages.calories_label') }}" name="calorias" type="number" placeholder="{{ __('messages.calories_placeholder') }}" />

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold">{{ __('messages.proteins_label') }}</label>
                    <input type="number" wire:model="proteinas"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                           placeholder="{{ __('messages.field_placeholder') }}">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold">{{ __('messages.carbs_label') }}</label>
                    <input type="number" wire:model="carbohidratos"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                           placeholder="{{ __('messages.field_placeholder') }}">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold">{{ __('messages.fats_label') }}</label>
                    <input type="number" wire:model="grasas"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                           placeholder="{{ __('messages.field_placeholder') }}">
                </div>
            </div>
        </div>

        @if (session()->has('error'))
            <div class="text-red-600 bg-red-100 border border-red-500 rounded-lg p-4 text-center mb-6">
                {{ session('error') }}
            </div>
        @endif

        {{-- NUEVO: componente para mostrar errores --}}
        <x-form.errors />

        {{-- Bloque original conservado --}}
        @if ($errors->any())
            <div class="text-red-600 bg-red-100 border border-red-500 rounded-lg p-4 text-center mb-6">
                @foreach ($errors->all() as $error)
                    <p>{{ __('messages.form_error', ['error' => $error]) }}</p>
                @endforeach
            </div>
        @endif

        <div class="mt-6 flex justify-between">
            <button wire:click="guardar"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition-all duration-300">
                {{ __('messages.save') }}
            </button>
            <a href="{{ route('dashboard') }}"
               class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg transition-all duration-300">
                {{ __('messages.cancel') }}
            </a>
        </div>
    </div>
</div>
