<div class="flex items-center justify-center min-h-screen bg-[#f8fff4]">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ __('messages.complete_info') }}</h2>
        <div class="text-center font-bold text-red-500">{{ __('messages.current_step', ['step' => $step]) }}</div>
        <div class="flex justify-end mb-2">
            @include('components.language-switcher')
        </div>

        @if (session()->has('message'))
            <div class="bg-green-500 text-white text-center py-2 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif

        @if(session()->has('debug'))
            <div class="text-red-500 font-bold">{{ session('debug') }}</div>
        @endif

        <form wire:submit.prevent="save">
            @csrf

            <!-- Paso 1 -->
            <div wire:key="step-{{ $step }}" @if($step != 1) hidden @endif>
                <label class="block text-gray-700">{{ __('messages.question_gender') }}</label>
                <select wire:model="gender" class="w-full px-4 py-2 border rounded-lg">
                    <option value="" disabled selected>{{ __('messages.select_gender') }}</option>
                    <option value="male">{{ __('messages.male') }}</option>
                    <option value="female">{{ __('messages.female') }}</option>
                </select>
                @error('gender') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Paso 2 -->
            <div class="{{ $step == 2 ? 'block' : 'hidden' }}">
                <label class="block text-gray-700">{{ __('messages.question_age') }}</label>
                <input type="number" wire:model="age" class="w-full px-4 py-2 border rounded-lg" min="10" max="100">
                @error('age') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Paso 3 -->
            <div class="{{ $step == 3 ? 'block' : 'hidden' }}">
                <label class="block text-gray-700">{{ __('messages.question_weight') }}</label>
                <input type="number" wire:model="peso" class="w-full px-4 py-2 border rounded-lg" min="30" max="300">
                @error('peso') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Paso 4 -->
            <div class="{{ $step == 4 ? 'block' : 'hidden' }}">
                <label class="block text-gray-700">{{ __('messages.question_height') }}</label>
                <input type="number" wire:model="altura" class="w-full px-4 py-2 border rounded-lg" min="100" max="250">
                @error('altura') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Paso 5 -->
            <div class="{{ $step == 5 ? 'block' : 'hidden' }}">
                <label class="block text-gray-700">{{ __('messages.question_goal') }}</label>
                <select wire:model="objetivo" class="w-full px-4 py-2 border rounded-lg">
                    <option value="" disabled selected>{{ __('messages.select_goal') }}</option>
                    <option value="perder_peso">{{ __('messages.lose_weight') }}</option>
                    <option value="mantener_peso">{{ __('messages.maintain_weight') }}</option>
                    <option value="ganar_musculo">{{ __('messages.gain_weight') }}</option>
                </select>
                @error('objetivo') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Paso 6 -->
            <div class="{{ $step == 6 ? 'block' : 'hidden' }}">
                <label class="block text-gray-700">{{ __('messages.question_activity') }}</label>
                <select wire:model="actividad" class="w-full px-4 py-2 border rounded-lg">
                    <option value="" disabled selected>{{ __('messages.select_activity') }}</option>
                    <option value="sedentario">{{ __('messages.sedentary_label') }}</option>
                    <option value="ligero">{{ __('messages.light_label') }}</option>
                    <option value="moderado">{{ __('messages.moderate_label') }}</option>
                    <option value="intenso">{{ __('messages.intense_label') }}</option>

                </select>
                @error('actividad') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mt-6 flex justify-between">
                @if ($step > 1)
                    <button type="button" wire:click="prevStep" wire:loading.attr="disabled"
                            class="px-4 py-2 bg-gray-300 rounded-lg transition hover:bg-gray-400">
                        ← {{ __('messages.back') }}
                    </button>
                @endif

                @if ($step < 6)
                    <button type="button" wire:click="nextStep" wire:loading.attr="disabled"
                            class="px-4 py-2 bg-[#96c464] text-white rounded-lg transition hover:bg-[#7ab940]">
                        {{ __('messages.next') }} →
                    </button>
                @else
                    <form wire:submit.prevent="save">
                        <button type="submit" wire:loading.attr="disabled">
                            {{ __('messages.finish') }} ✅
                        </button>
                    </form>
                @endif
            </div>
        </form>
    </div>

    <div>
        @error('gender') <span class="text-red-500">{{ $message }}</span> @enderror
        @error('age') <span class="text-red-500">{{ $message }}</span> @enderror
        @error('peso') <span class="text-red-500">{{ $message }}</span> @enderror
        @error('altura') <span class="text-red-500">{{ $message }}</span> @enderror
        @error('objetivo') <span class="text-red-500">{{ $message }}</span> @enderror
        @error('actividad') <span class="text-red-500">{{ $message }}</span> @enderror
    </div>
</div>
