<div class="flex justify-center items-center min-h-screen bg-[#f8fff4]">
    <div class="bg-white shadow-xl rounded-2xl p-8 border border-gray-300 w-full max-w-md">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">{{ __('messages.edit_food') }}</h2>

        <div class="space-y-4">
            {{-- Componente input para nombre (desactivado) --}}
            <x-form.input label="{{ __('messages.food_name') }}" name="nombre" :value="$alimento->alimento->nombre" disabled />

            {{-- Componente input para cantidad --}}
            <x-form.input label="{{ __('messages.amount_g') }}" name="cantidad" type="number" placeholder="{{ __('messages.amount_placeholder') }}" />

            <div class="text-gray-700">
                <p><strong>{{ __('messages.calories_static') }}</strong> {{ $alimento->alimento->calorias }} kcal</p>
                <p><strong>{{ __('messages.proteins_static') }}</strong> {{ $alimento->alimento->proteinas }} g</p>
                <p><strong>{{ __('messages.carbs_static') }}</strong> {{ $alimento->alimento->carbohidratos }} g</p>
                <p><strong>{{ __('messages.fats_static') }}</strong> {{ $alimento->alimento->grasas }} g</p>
            </div>
        </div>

        <div class="mt-6 flex justify-between">
            <button wire:click="actualizar"
                    class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-6 rounded-lg transition-all duration-300">
                {{ __('messages.save_changes') }}
            </button>
            <button wire:click="eliminar"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg transition-all duration-300">
                {{ __('messages.delete') }}
            </button>
        </div>

        @if (session()->has('error'))
            <div class="text-red-600 bg-red-100 border border-red-500 rounded-lg p-4 text-center mb-6">
                {{ session('error') }}
            </div>
        @endif

        {{-- NUEVO --}}
        <x-form.errors />

        {{-- ORIGINAL --}}
        @if ($errors->any())
            <div class="text-red-600 bg-red-100 border border-red-500 rounded-lg p-4 text-center mb-6">
                @foreach ($errors->all() as $error)
                    <p>❌ {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="mt-4 text-center">
            <a href="{{ route('dashboard') }}" class="text-gray-500 hover:underline">{{ __('messages.back') }}</a>
        </div>
    </div>
</div>
