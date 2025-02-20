


<div class="flex items-center justify-center min-h-screen bg-[#f8fff4]">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Completa tu información</h2>
        <div class="text-center font-bold text-red-500">Paso actual: {{ $step }}</div>

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

            <!-- Pregunta 1: Género -->
            <div wire:key="step-{{ $step }}" @if($step != 1) hidden @endif>
                <label class="block text-gray-700">¿Cuál es tu género?</label>
                <select wire:model="gender" class="w-full px-4 py-2 border rounded-lg">
                    <option value="" disabled selected>Selecciona tu género</option>
                    <option value="male">Hombre</option>
                    <option value="female">Mujer</option>
                </select>
                @error('gender') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Pregunta 2: Edad -->
            <div class="{{ $step == 2 ? 'block' : 'hidden' }}">
                <label class="block text-gray-700">¿Cuál es tu edad?</label>
                <input type="number" wire:model="age" class="w-full px-4 py-2 border rounded-lg" min="10" max="100">
                @error('age') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Pregunta 3: Peso -->
            <div class="{{ $step == 3 ? 'block' : 'hidden' }}">
                <label class="block text-gray-700">¿Cuál es tu peso actual? (kg)</label>
                <input type="number" wire:model="peso" class="w-full px-4 py-2 border rounded-lg" min="30" max="300">
                @error('peso') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Pregunta 4: Altura -->
            <div class="{{ $step == 4 ? 'block' : 'hidden' }}">
                <label class="block text-gray-700">¿Cuál es tu altura? (cm)</label>
                <input type="number" wire:model="altura" class="w-full px-4 py-2 border rounded-lg" min="100" max="250">
                @error('altura') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Pregunta 5: Objetivo -->
            <div class="{{ $step == 5 ? 'block' : 'hidden' }}">
                <label class="block text-gray-700">¿Cuál es tu objetivo?</label>
                <select wire:model="objetivo" class="w-full px-4 py-2 border rounded-lg">
                    <option value="" disabled selected>Selecciona un objetivo</option>
                    <option value="perder_peso">Bajar de peso</option>
                    <option value="mantener_peso">Mantener peso</option>
                    <option value="ganar_musculo">Subir de peso</option>
                </select>
                @error('objetivo') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Pregunta 6: Actividad física -->
            <div class="{{ $step == 6 ? 'block' : 'hidden' }}">
                <label class="block text-gray-700">¿Cuál es tu nivel de actividad física?</label>
                <select wire:model="actividad" class="w-full px-4 py-2 border rounded-lg">
                    <option value="" disabled selected>Selecciona tu nivel de actividad</option>
                    <option value="sedentario">Sedentario</option>
                    <option value="ligero">Ligero</option>
                    <option value="moderado">Moderado</option>
                    <option value="intenso">Intenso</option>
                </select>
                @error('actividad') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mt-6 flex justify-between">
                @if ($step > 1)
                    <button type="button" wire:click="prevStep" wire:loading.attr="disabled"
                            class="px-4 py-2 bg-gray-300 rounded-lg transition hover:bg-gray-400">
                        ← Atrás
                    </button>
                @endif

                @if ($step < 6)
                        <button type="button" wire:click="nextStep" wire:loading.attr="disabled"
                                class="px-4 py-2 bg-[#96c464] text-white rounded-lg transition hover:bg-[#7ab940]">
                            Siguiente →
                        </button>

                    @else
                        <form wire:submit.prevent="save">
                            <button type="submit" wire:loading.attr="disabled">
                                Finalizar ✅
                            </button>
                        </form>


                    @endif
            </div>

        </form> <!-- 🔥 Se cerró correctamente el <form> -->
    </div>
    <div>
        @error('gender') <span class="text-red-500">{{ $message }}</span> @enderror
        @error('age') <span class="text-red-500">{{ $message }}</span> @enderror
        @error('peso') <span class="text-red-500">{{ $message }}</span> @enderror
        @error('altura') <span class="text-red-500">{{ $message }}</span> @enderror
        @error('objetivo') <span class="text-red-500">{{ $message }}</span> @enderror
        @error('actividad') <span class="text-red-500">{{ $message }}</span> @enderror
    </div>
    <!-- 🔥 Se cerró correctamente el div contenedor del formulario -->
</div> <!-- 🔥 Se cerró correctamente el div principal -->


