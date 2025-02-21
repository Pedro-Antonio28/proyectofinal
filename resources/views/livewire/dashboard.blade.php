<div class="grid grid-cols-12 gap-6 p-8 min-h-screen bg-[#f8fff4] pt-24">
    @include('components.navbar')

    <!-- 🏆 Sidebar: Progreso de Macronutrientes -->
    <aside class="col-span-4 bg-[#e5f2d8] shadow-xl rounded-2xl p-6 h-full border border-gray-300">
        <h3 class="text-lg font-bold text-gray-800 mb-4">🎯 Progreso del Día</h3>

        <div class="grid grid-cols-2 gap-4 text-center">
            @foreach ([
                ['🔥', 'Calorías', $caloriasConsumidas, $caloriasTotales, 'red'],
                ['🥩', 'Proteínas', $proteinasConsumidas, $proteinasTotales, 'blue'],
                ['🍞', 'Carbohidratos', $carbohidratosConsumidos, $carbohidratosTotales, 'orange'],
                ['🥑', 'Grasas', $grasasConsumidas, $grasasTotales, 'green']
            ] as [$emoji, $nombre, $consumido, $total, $color])
                <div>
                    <svg width="100" height="100">
                        <circle cx="50" cy="50" r="40" stroke="gray" stroke-width="10" fill="none"></circle>
                        <circle cx="50" cy="50" r="40"
                                stroke="{{ $color }}"
                                stroke-width="10"
                                fill="none"
                                stroke-dasharray="251"
                                stroke-dashoffset="{{ 251 - (251 * ($consumido / max(1, $total))) }}">
                        </circle>
                        <text x="50" y="55" font-size="14" text-anchor="middle" fill="black">{{ round(($consumido / max(1, $total)) * 100) }}%</text>
                    </svg>
                    <p class="text-gray-800 font-bold mt-2">{{ $emoji }} {{ $nombre }}</p>
                    <p class="text-gray-600 text-sm">{{ round($consumido) }} / {{ round($total) }}</p>
                </div>
            @endforeach
        </div>
    </aside>

    <!-- 🍽️ Contenido: Dieta del Día -->
    <section class="col-span-8 bg-white shadow-xl rounded-2xl p-8 border border-gray-300">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-900">📅 Dieta para {{ ucfirst($diaActual) }}</h3>

            <!-- 🔥 Selector de Día -->
            <select wire:model="diaActual" wire:change="cambiarDia($event.target.value)"
                    class="border border-gray-300 rounded-lg px-4 py-2 bg-white text-gray-800 shadow-md transition-all duration-300 hover:border-gray-400 focus:border-[#96c464]">
                @foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $dia)
                    <option value="{{ $dia }}">{{ ucfirst($dia) }}</option>
                @endforeach
            </select>
        </div>

        @foreach ($comidas as $tipoComida => $alimentos)
            <div class="mb-8">
                <h4 class="text-xl font-bold text-gray-800 border-b-2 border-[#a7d675] pb-2">{{ $tipoComida }}</h4>
                <div class="grid grid-cols-3 gap-6 mt-4">
                    @foreach ($alimentos as $comida)
                        <div class="bg-[#f0f9eb] p-5 rounded-2xl shadow-md flex flex-col items-center border border-gray-300">
                            <h5 class="text-md font-semibold text-center text-gray-900">{{ $comida->alimento->nombre }}</h5>
                            <p class="text-gray-600 text-sm text-center">{{ $comida->cantidad }}g - {{ $comida->alimento->calorias }} kcal</p>
                            <input type="checkbox" wire:click="toggleAlimento({{ $comida->alimento_id }})"
                                {{ in_array($comida->alimento_id, $alimentosConsumidos) ? 'checked' : '' }}>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </section>
</div>
