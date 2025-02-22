<div class="grid grid-cols-12 gap-6 p-8 min-h-screen bg-[#f8fff4] pt-24">
    @include('components.navbar')

    <!-- 🏆 Sidebar: Progreso de Macronutrientes -->
    <aside class="col-span-4 bg-[#e5f2d8] shadow-xl rounded-2xl p-6 h-full border border-gray-300">
        <h3 class="text-lg font-bold text-gray-800 mb-4">🎯 Progreso del Día</h3>

        <div class="grid grid-cols-2 gap-6 text-center">
            @foreach ([
                ['🔥', 'Calorías', $caloriasConsumidas, $caloriasTotales, '#FF5733'],
                ['🥩', 'Proteínas', $proteinasConsumidas, $proteinasTotales, '#3498db'],
                ['🍞', 'Carbohidratos', $carbohidratosConsumidos, $carbohidratosTotales, '#f39c12'],
                ['🥑', 'Grasas', $grasasConsumidas, $grasasTotales, '#2ecc71']
            ] as [$emoji, $nombre, $consumido, $total, $color])
                <div class="relative flex flex-col items-center">
                    <svg width="120" height="120" viewBox="0 0 120 120">
                        <!-- Círculo de fondo -->
                        <circle cx="60" cy="60" r="50" stroke="#ddd" stroke-width="10" fill="none"></circle>

                        <!-- Círculo de progreso con animación -->
                        <circle cx="60" cy="60" r="50"
                                stroke="{{ $color }}"
                                stroke-width="10"
                                fill="none"
                                stroke-linecap="round"
                                stroke-dasharray="314"
                                stroke-dashoffset="{{ max(0, 314 - (314 * min(1, round($consumido / max(1, $total), 5)) )) }}"

                                style="transition: stroke-dashoffset 0.6s ease-in-out;">
                        </circle>

                        <!-- Porcentaje en el centro -->
                        <text x="60" y="65" font-size="18" text-anchor="middle" fill="{{ $color }}" font-weight="bold">
                            {{ round(($consumido / max(1, $total)) * 100) }}%
                        </text>
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

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                Livewire.on('refreshUI', () => {
                    Livewire.refresh(); // 🔄 Forzar actualización de la UI en Livewire
                });
            });
        </script>

        @foreach ($comidas as $tipoComida => $alimentos)
            <div class="mb-8">
                <h4 class="text-xl font-bold text-gray-800 border-b-2 border-[#a7d675] pb-2 flex justify-between items-center">
                    {{ $tipoComida }}

                    <!-- Botón para añadir alimento -->
                    <a href="{{ route('agregar.alimento', ['dia' => $diaActual, 'tipoComida' => $tipoComida]) }}"
                       class="bg-green-500 text-white px-3 py-1 rounded-md text-sm font-semibold hover:bg-green-700 transition-all duration-300">
                        ➕ Añadir
                    </a>
                </h4>

                <div class="grid grid-cols-3 gap-6 mt-4">
                    @foreach ($alimentos as $comida)
                        <!-- Evitar generar enlaces rotos -->
                        @if(isset($comida['alimento_id']))
                            <a href="{{ route('editar.alimento', ['dia' => $diaActual, 'tipoComida' => $tipoComida, 'alimentoId' => $comida['alimento_id']]) }}"
                               class="bg-[#f0f9eb] p-5 rounded-2xl shadow-md flex flex-col items-center border border-gray-300
                              hover:scale-105 hover:shadow-lg transition-all duration-300 cursor-pointer relative">

                                <h5 class="text-md font-semibold text-center text-gray-900">{{ $comida['nombre'] }}</h5>
                                <p class="text-gray-600 text-sm text-center">
                                    {{ $comida['cantidad'] }}g - {{ $comida['calorias'] }} kcal
                                </p>

                                <!-- Checkbox solo si es el día actual -->
                                @if ($esDiaActual)
                                    <input type="checkbox" wire:click.prevent="toggleAlimento({{ $comida['alimento_id'] }})"
                                           {{ in_array($comida['alimento_id'], $alimentosConsumidos) ? 'checked' : '' }}
                                           class="mt-2">
                                @endif
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach




    </section>
</div>
