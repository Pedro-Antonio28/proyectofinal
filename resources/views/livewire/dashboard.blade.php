<div class="grid grid-cols-12 gap-6 p-8 min-h-screen bg-[#f8fff4] pt-32">
    @include('components.navbar')

    <!-- 🏆 Sidebar -->
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
                        <circle cx="60" cy="60" r="50" stroke="#ddd" stroke-width="10" fill="none"></circle>
                        <circle cx="60" cy="60" r="50"
                                stroke="{{ $color }}"
                                stroke-width="10"
                                fill="none"
                                stroke-linecap="round"
                                stroke-dasharray="314"
                                stroke-dashoffset="{{ max(0, 314 - (314 * min(1, round($consumido / max(1, $total), 5)) )) }}"
                                style="transition: stroke-dashoffset 0.6s ease-in-out;">
                        </circle>
                        <text x="60" y="65" font-size="18" text-anchor="middle" fill="{{ $color }}" font-weight="bold">
                            {{ round(($consumido / max(1, $total)) * 100) }}%
                        </text>
                    </svg>
                    <p class="text-gray-800 font-bold mt-2">{{ $emoji }} {{ $nombre }}</p>
                    <p class="text-gray-600 text-sm">{{ round($consumido) }} / {{ round($total) }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-8 flex flex-col gap-3">
            <a href="{{ route('pdf.dieta', ['dia' => $diaActual]) }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-md shadow-md text-center hover:bg-blue-700 transition">
                📄 Descargar dieta de {{ $diaActual }}
            </a>

            <button wire:click="enviarDietaSemanalPorCorreo"
                    type="button"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-md shadow-md hover:bg-indigo-700 transition">
                📬 Enviar dieta semanal por correo
            </button>
        </div>

        <form wire:submit.prevent="enviarDietaPorTelegram" class="mt-3">
            <button type="submit"
                    class="w-full bg-green-600 text-white px-4 py-2 rounded-md shadow-md text-center hover:bg-green-700 transition">
                📤 Enviar dieta por Telegram
            </button>
        </form>
    </aside>

    <!-- 🍽️ Dieta -->
    <section class="col-span-8 bg-white shadow-xl rounded-2xl p-8 border border-gray-300">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-900">📅 Dieta para {{ ucfirst($diaActual) }}</h3>

            <div class="flex items-center space-x-4 mb-6">
                <select wire:model="diaActual" wire:change="$refresh"
                        class="border border-gray-300 rounded-lg px-4 py-2 bg-white text-gray-800 shadow-md transition-all duration-300 hover:border-gray-400 focus:border-[#96c464]">
                    @foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $dia)
                        <option value="{{ $dia }}">{{ ucfirst($dia) }}</option>
                    @endforeach
                </select>

                <form wire:submit.prevent="exportarExcel">
                    <button type="submit"
                            class="bg-emerald-600 text-white px-4 py-2 rounded-md shadow-md transition-all duration-300 hover:bg-emerald-700">
                        Exportar a Excel
                    </button>
                </form>
            </div>

            <div wire:key="dashboard-{{ $dummy }}"></div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                window.addEventListener('refresh-ui', () => Livewire.refresh());
                window.addEventListener('force-update', () => setTimeout(() => Livewire.refresh(), 50));
            });

            function abrirModalPremium() {
                document.getElementById('modalOverlayPremium').classList.remove('hidden');
            }

            function cerrarModalPremium() {
                document.getElementById('modalOverlayPremium').classList.add('hidden');
            }
        </script>

        @foreach (['Desayuno', 'Almuerzo', 'Comida', 'Merienda', 'Cena'] as $tipoComida)
            <div class="mb-8">
                <h4 class="text-xl font-bold text-gray-800 border-b-2 border-[#a7d675] pb-2 flex justify-between items-center">
                    {{ $tipoComida }}

                    @if(auth()->user()->is_premium)
                        <a href="{{ route('agregar.alimento', ['dia' => $diaActual, 'tipoComida' => $tipoComida]) }}"
                           class="bg-green-500 text-white px-3 py-1 rounded-md text-sm font-semibold hover:bg-green-700 transition-all duration-300">
                            ➕ Añadir
                        </a>
                    @else
                        <button type="button"
                                onclick="abrirModalPremium()"
                                class="bg-green-500 text-white px-3 py-1 rounded-md text-sm font-semibold hover:bg-green-700 transition-all duration-300">
                            ➕ Añadir
                        </button>
                    @endif
                </h4>

                <div class="grid grid-cols-3 gap-6 mt-4">
                    @if(isset($comidas[$tipoComida]))
                        @foreach ($comidas[$tipoComida] as $comida)
                            <a href="{{ route('editar.alimento', ['dia' => $diaActual, 'tipoComida' => $tipoComida, 'alimentoId' => $comida['alimento_id']]) }}"
                               class="bg-[#f0f9eb] p-5 rounded-2xl shadow-md flex flex-col items-center border border-gray-300 hover:scale-105 hover:shadow-lg transition-all duration-300 cursor-pointer">
                                <h5 class="text-md font-semibold text-center text-gray-900">{{ $comida['nombre'] }}</h5>
                                <p class="text-gray-600 text-sm text-center">
                                    {{ $comida['cantidad'] }}g - {{ $comida['calorias'] }} kcal
                                </p>

                                @if ($esDiaActual)
                                    <input type="checkbox" wire:click.prevent="toggleAlimento({{ $comida['alimento_id'] }})"
                                           {{ in_array($comida['alimento_id'], $alimentosConsumidos) ? 'checked' : '' }}
                                           class="mt-2">
                                @endif
                            </a>
                        @endforeach
                    @else
                        <p>No hay alimentos asignados.</p>
                    @endif
                </div>
            </div>
        @endforeach

        <style>
            a, h1, h2, h3, h4, h5, h6, p, span {
                text-decoration: none !important;
            }

            @keyframes fade-in {
                from {
                    opacity: 0;
                    transform: scale(0.95);
                }
                to {
                    opacity: 1;
                    transform: scale(1);
                }
            }
            .animate-fade-in {
                animation: fade-in 0.25s ease-out forwards;
            }
        </style>

        <!-- 🔒 Modal Premium -->
        @if (!auth()->user()->is_premium)
            <div id="modalOverlayPremium"
                 class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm px-4">
                <div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-6 transform transition-all scale-95 animate-fade-in">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4 text-center">🚀 Función Premium</h2>
                    <p class="text-gray-600 text-center mb-6 leading-relaxed">
                        Añadir alimentos a tu dieta es una función premium.<br>
                        Desbloquéala por solo <strong>4.99€</strong> y accede a todos los beneficios.
                    </p>

                    <div class="w-20 h-20 mx-auto mb-6 animate-bounce text-yellow-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l2.9 6.6L22 9.3l-5.5 5.1L17.8 22 12 18.4 6.2 22l1.3-7.6L2 9.3l7.1-0.7z"/>
                        </svg>
                    </div>


                    <div class="flex justify-center gap-4">
                        <a href="{{ route('paypal.create') }}"
                           class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-lg font-semibold shadow transition">
                            💳 Comprar Premium
                        </a>
                        <button onclick="cerrarModalPremium()"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-5 py-2 rounded-lg font-semibold transition">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        @endif

    </section>

    @if ($mostrarModalTelegram)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">
            <div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-6 animate-fade-in">
                <h2 class="text-2xl font-bold text-gray-900 text-center mb-4">🤖 Conectar con Telegram</h2>

                <p class="text-gray-600 text-center mb-4">
                    Habla con <a href="https://t.me/RealCalorix_bot" target="_blank" class="text-blue-600 underline">@RealCalorix_bot</a>
                    y obtén tu <strong>Telegram ID</strong> usando <a href="https://t.me/userinfobot" target="_blank" class="text-blue-600 underline">@userinfobot</a>.
                </p>

                <form wire:submit.prevent="guardarTelegramId" class="space-y-4">
                    <input type="text" wire:model="nuevoTelegramId"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-green-300"
                           placeholder="Ej: 123456789" />

                    @error('nuevoTelegramId') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror

                    <div class="flex justify-between mt-4">
                        <button type="button" wire:click="$set('mostrarModalTelegram', false)"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition">
                            Cancelar
                        </button>

                        <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold shadow transition">
                            ✅ Guardar ID y vincular
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
