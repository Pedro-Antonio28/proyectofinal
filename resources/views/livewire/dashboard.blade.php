<div class="grid grid-cols-12 gap-4 p-6 min-h-screen bg-gray-100">
    <!-- 🏆 Sidebar: Meta del Día -->
    <aside class="col-span-3 bg-white shadow-lg rounded-lg p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">🎯 Meta del Día</h3>

        <div class="text-gray-700">
            <p><strong>Calorías:</strong> {{ Auth::user()->calorias_necesarias }} kcal</p>
            <p><strong>Proteínas:</strong> {{ Auth::user()->proteinas }} g</p>
            <p><strong>Carbohidratos:</strong> {{ Auth::user()->carbohidratos }} g</p>
            <p><strong>Grasas:</strong> {{ Auth::user()->grasas }} g</p>
        </div>

        <!-- Barra de progreso -->
        <div class="mt-4">
            <p class="text-sm text-gray-600">📊 Progreso:</p>
            <div class="bg-gray-200 rounded-full h-4">
                <div class="bg-green-500 h-4 rounded-full" style="width: 50%;"></div>
            </div>
        </div>
    </aside>

    <!-- 🍽️ Contenido: Dieta del Día -->
    <section class="col-span-9 bg-white shadow-lg rounded-lg p-6">
        <div class="flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800">📅 Dieta para {{ ucfirst($diaActual) }}</h3>

            <!-- Botón para cambiar de día -->
            <select wire:model="diaActual" class="border border-gray-300 rounded-lg px-3 py-2">
                @foreach ($dieta as $dia => $info)
                    <option value="{{ $dia }}">{{ ucfirst($dia) }}</option>
                @endforeach
            </select>
        </div>

        @if (isset($dieta[$diaActual]))
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                @foreach ($dieta[$diaActual]['comidas'] as $comida)
                    <div class="bg-gray-50 p-4 rounded-lg shadow">
                        <h4 class="text-md font-semibold">{{ $comida['nombre'] }}</h4>
                        <p class="text-gray-600 text-sm">{{ $comida['cantidad'] }}g - {{ $comida['calorias'] }} kcal</p>
                        <p class="text-xs text-gray-500">
                            Proteínas: {{ $comida['proteinas'] }}g |
                            Carbs: {{ $comida['carbohidratos'] }}g |
                            Grasas: {{ $comida['grasas'] }}g
                        </p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-red-500 mt-4">❌ No hay datos para este día.</p>
        @endif
    </section>
</div>
