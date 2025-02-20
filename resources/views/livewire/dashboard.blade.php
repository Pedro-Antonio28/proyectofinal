<div class="grid grid-cols-12 gap-6 p-6 min-h-screen bg-gray-100">
    <!-- 🏆 Sidebar: Meta del Día (IZQUIERDA) -->
    <aside class="col-span-4 bg-white shadow-lg rounded-lg p-6 h-full flex flex-col justify-between">
        <div>
            <h3 class="text-lg font-bold text-gray-800 mb-4">🎯 Meta del Día</h3>

            <div class="text-gray-700 space-y-2">
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
        </div>

        <!-- 🔴 BOTÓN DE CERRAR SESIÓN -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded w-full mt-6">
                🚪 Cerrar Sesión
            </button>
        </form>
    </aside>

    <!-- 🍽️ Contenido: Dieta del Día (DERECHA) -->
    <section class="col-span-8 bg-white shadow-lg rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-800">📅 Dieta para {{ ucfirst($diaActual) }}</h3>

            <!-- Botón para cambiar de día -->
            <select wire:model="diaActual" class="border border-gray-300 rounded-lg px-3 py-2">
                @foreach ($dieta as $dia => $info)
                    <option value="{{ $dia }}">{{ ucfirst($dia) }}</option>
                @endforeach
            </select>
        </div>

        @if ($dieta && array_key_exists($diaActual, $dieta) && count($dieta[$diaActual]['comidas']) > 0)
            <div class="grid grid-cols-3 gap-4">
                @foreach ($dieta[$diaActual]['comidas'] as $comida)
                    <div class="bg-gray-50 p-4 rounded-lg shadow flex flex-col items-center">
                        <h4 class="text-md font-semibold text-center">{{ $comida['nombre'] }}</h4>
                        <p class="text-gray-600 text-sm text-center">{{ $comida['cantidad'] }}g - {{ $comida['calorias'] }} kcal</p>
                        <p class="text-xs text-gray-500 text-center">
                            🥩 {{ $comida['proteinas'] }}g | 🍞 {{ $comida['carbohidratos'] }}g | 🥑 {{ $comida['grasas'] }}g
                        </p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-red-500 mt-4 text-center">❌ No hay comidas registradas para este día.</p>
        @endif
    </section>
</div>
