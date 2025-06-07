<div class="grid grid-cols-12 gap-6 p-8 min-h-screen bg-[#f8fff4] pt-32">
    @include('components.navbar')

    <!-- 🏆 Sidebar -->
    <aside class="col-span-4 bg-[#e5f2d8] shadow-xl rounded-2xl p-6 h-full border border-gray-300">
        <h3 class="text-lg font-bold text-gray-800 mb-4">{{ __('messages.daily_progress') }}</h3>

        <div class="grid grid-cols-2 gap-6 text-center">
            @foreach ([
                    ['🔥', __('messages.calories'), $caloriasConsumidas, $caloriasTotales, '#FF5733'],
                    ['🥩', __('messages.proteins'), $proteinasConsumidas, $proteinasTotales, '#3498db'],
                    ['🍞', __('messages.carbohydrates'), $carbohidratosConsumidos, $carbohidratosTotales, '#f39c12'],
                    ['🥑', __('messages.fats'), $grasasConsumidas, $grasasTotales, '#2ecc71']
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
                {{ __('messages.download_diet') }} {{ $diaActual }}
            </a>

            <button wire:click="enviarDietaSemanalPorCorreo"
                    type="button"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-md shadow-md hover:bg-indigo-700 transition">
                {{ __('messages.send_weekly_email') }}
            </button>
        </div>

        <form wire:submit.prevent="enviarDietaPorTelegram" class="mt-3">
            <button type="submit"
                    class="w-full bg-green-600 text-white px-4 py-2 rounded-md shadow-md text-center hover:bg-green-700 transition">
                {{ __('messages.send_telegram') }}
            </button>
        </form>
    </aside>

    <!-- 🍽️ Dieta -->
    <section class="col-span-8 bg-white shadow-xl rounded-2xl p-8 border border-gray-300">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-900">{{ __('messages.diet_for') }} {{ ucfirst($diaActual) }}</h3>

            <div class="flex items-center space-x-4 mb-6">
                <x-form.select name="diaActual" label="" wire:model="diaActual" wire:change="$refresh"
                               :options="['Lunes' => 'Lunes', 'Martes' => 'Martes', 'Miércoles' => 'Miércoles', 'Jueves' => 'Jueves', 'Viernes' => 'Viernes', 'Sábado' => 'Sábado', 'Domingo' => 'Domingo']"
                               class="shadow-md transition-all duration-300 hover:border-gray-400 focus:border-[#96c464]" />


                <form wire:submit.prevent="exportarExcel">
                    <button type="submit"
                            class="bg-emerald-600 text-white px-4 py-2 rounded-md shadow-md transition-all duration-300 hover:bg-emerald-700">
                        {{ __('messages.export_excel') }}
                    </button>
                </form>
            </div>
        </div>

        @foreach (['Desayuno', 'Almuerzo', 'Comida', 'Merienda', 'Cena'] as $tipoComida)
            <div class="mb-8">
                <h4 class="text-xl font-bold text-gray-800 border-b-2 border-[#a7d675] pb-2 flex justify-between items-center">
                    {{ $tipoComida }}

                    @if(auth()->user()->is_premium)
                        <a href="{{ route('agregar.alimento', ['dia' => $diaActual, 'tipoComida' => $tipoComida]) }}"
                           class="bg-green-500 text-white px-3 py-1 rounded-md text-sm font-semibold hover:bg-green-700 transition-all duration-300">
                            {{ __('messages.add') }}
                        </a>
                    @else
                        <button type="button"
                                onclick="abrirModalPremium()"
                                class="bg-green-500 text-white px-3 py-1 rounded-md text-sm font-semibold hover:bg-green-700 transition-all duration-300">
                            {{ __('messages.add') }}
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
                                    <x-form.checkbox
                                        :id="'alimento_' . $comida['alimento_id']"
                                        name="alimento_{{ $comida['alimento_id'] }}"
                                        label=""
                                        :checked="in_array($comida['alimento_id'], $alimentosConsumidos)"
                                        wire:click.prevent="toggleAlimento({{ $comida['alimento_id'] }})"
                                    />


                                @endif
                            </a>
                        @endforeach
                    @else
                        <p>{{ __('messages.no_food_assigned') }}</p>
                    @endif
                </div>
            </div>
        @endforeach

        @include('components.modal-premium')
    </section>

    @if ($mostrarModalTelegram)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">
            <div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-6 animate-fade-in">
                <h2 class="text-2xl font-bold text-gray-900 text-center mb-4">{{ __('messages.connect_telegram') }}</h2>

                <p class="text-gray-600 text-center mb-4">
                    {!! __('messages.get_telegram_id') !!}
                </p>

                <form wire:submit.prevent="guardarTelegramId" class="space-y-4">
                    <x-form.input name="nuevoTelegramId" label="" wire:model="nuevoTelegramId"
                                  placeholder="{{ __('messages.placeholder_telegram_id') }}" />
                    <x-form.error field="nuevoTelegramId" />


                    @error('nuevoTelegramId') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror

                    <div class="flex justify-between mt-4">
                        <button type="button" wire:click="$set('mostrarModalTelegram', false)"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition">
                            {{ __('messages.cancel') }}
                        </button>

                        <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold shadow transition">
                            {{ __('messages.save_telegram_id') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif


</div>
