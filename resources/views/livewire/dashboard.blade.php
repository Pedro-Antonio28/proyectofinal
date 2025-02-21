

<div class="grid grid-cols-12 gap-6 p-8 min-h-screen bg-[#f8fff4] pt-24">
    @include('components.navbar')

    <!-- 🏆 Sidebar: Meta del Día -->
    <aside class="col-span-4 bg-[#e5f2d8] shadow-xl rounded-2xl p-6 h-full flex flex-col justify-between border border-gray-300">
        <div>
            <h3 class="text-lg font-bold text-gray-800 mb-4">🎯 Progreso del Día</h3>

            <div class="text-gray-700 space-y-2">
                <p><strong>🔥 Calorías:</strong> {{ Auth::user()->calorias_necesarias }} kcal</p>
                <p><strong>🥩 Proteínas:</strong> {{ Auth::user()->proteinas }} g</p>
                <p><strong>🍞 Carbohidratos:</strong> {{ Auth::user()->carbohidratos }} g</p>
                <p><strong>🥑 Grasas:</strong> {{ Auth::user()->grasas }} g</p>
            </div>

            <!-- 🔥 Barra de progreso que aumenta al marcar alimentos -->
            @php
                $totalComidas = $comidas->flatten(1)->count();
                $comidasSeleccionadas = $esDiaActual ? count($alimentosConsumidos ?? []) : 0;
                $progreso = $totalComidas > 0 ? ($comidasSeleccionadas / $totalComidas) * 100 : 0;
            @endphp

            <div class="mt-4">
                <p class="text-sm text-gray-600">📊 Progreso de comidas: {{ round($progreso) }}%</p>
                <div class="bg-gray-200 rounded-full h-4">
                    <div class="bg-green-500 h-4 rounded-full transition-all duration-300" style="width: {{ $progreso }}%;"></div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg w-full mt-6 shadow-md transition-all duration-300 transform hover:scale-105">
                🚪 Cerrar Sesión
            </button>
        </form>
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

        <!-- 🍽️ Mostrar la dieta del día seleccionado -->
        @php
            $dietaJson = json_decode($dieta->dieta, true);
        @endphp

        @foreach (['Desayuno', 'Almuerzo', 'Comida', 'Merienda', 'Cena'] as $tipoComida)
            @if (!empty($dietaJson[$diaActual][$tipoComida]))
                <div class="mb-8">
                    <h4 class="text-xl font-bold text-gray-800 border-b-2 border-[#a7d675] pb-2">{{ $tipoComida }}</h4>
                    <div class="grid grid-cols-3 gap-6 mt-4">
                        @foreach ($dietaJson[$diaActual][$tipoComida] as $comida)
                            <div class="bg-[#f0f9eb] p-5 rounded-2xl shadow-md flex flex-col items-center border border-gray-300">
                                <h5 class="text-md font-semibold text-center text-gray-900">{{ $comida['nombre'] }}</h5>
                                <p class="text-gray-600 text-sm text-center">{{ $comida['cantidad'] }}g - {{ $comida['calorias'] }} kcal</p>
                                <p class="text-xs text-gray-500 text-center">
                                    🥩 {{ round($comida['proteinas'], 1) }}g |
                                    🍞 {{ round($comida['carbohidratos'], 1) }}g |
                                    🥑 {{ round($comida['grasas'], 1) }}g
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach

    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            Livewire.on('dietaActualizada', () => {
                location.reload(); // 🔄 Recargar para ver los cambios de inmediato
            });
        });
        document.addEventListener("DOMContentLoaded", function () {
            Livewire.on('refreshCheckboxes', () => {
                document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                    cb.checked = cb.hasAttribute('checked');
                });
            });
        });
    </script>
</div>
