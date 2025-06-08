<div>
    <h1 class="text-4xl font-bold text-gray-900 text-center mb-8">{{ __('messages.select_favorite_foods') }}</h1>
    <div class="flex justify-end mb-2">
        @include('components.language-switcher')
    </div>

    <form wire:submit.prevent="guardarSeleccion" class="space-y-8">
        @csrf

        @php
            $categorias = [
                'proteinas' => __('messages.proteins'),
                'carbohidratos' => __('messages.carbohydrates'),
                'frutas' => __('messages.fruits'),
                'verduras' => __('messages.vegetables'),
                'grasas' => __('messages.fats')
            ];
        @endphp

        @foreach($categorias as $key => $nombre)
            <h2 class="text-2xl font-semibold text-gray-900 border-b-2 border-[#a7d675] pb-2">{{ $nombre }}</h2>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mt-4 justify-center">
                @foreach($alimentos->where('categoria', $key) as $alimento)
                    <label class="relative group cursor-pointer transition transform hover:scale-105 flex flex-col items-center w-40 mx-auto">

                        <input type="checkbox" wire:model="favoritos" value="{{ $alimento->id }}" class="hidden peer">

                        <div class="w-full bg-[#f0f9eb] rounded-xl shadow-md p-3 text-center border border-gray-300
                            peer-checked:bg-[#a7d675] peer-checked:border-[#7ab940] peer-checked:shadow-lg peer-checked:scale-105 transition-all duration-300">

                            @if($alimento->imagen)
                                @php
                                    $imagePath = file_exists(public_path('alimentos/' . $alimento->imagen))
                                        ? asset('alimentos/' . $alimento->imagen)
                                        : asset('storage/' . $alimento->imagen);
                                @endphp

                                <img src="{{ $imagePath }}" alt="{{ $alimento->nombre }}"
                                     class="img-fluid mb-2"
                                     style="max-height: 150px; object-fit: cover;">
                            @else
                                <img src="{{ asset('images/default-food.png') }}" alt="{{ __('messages.image_not_available') }}"
                                     class="img-fluid mb-2"
                                     style="max-height: 150px; object-fit: cover;">
                            @endif

                            <strong class="text-gray-900 text-base block peer-checked:text-white">
                                {{ $alimento->nombre }}
                            </strong>
                        </div>
                    </label>
                @endforeach
            </div>
        @endforeach

        <div class="flex justify-center mt-6">
            <button type="submit" class="bg-[#a7d675] hover:bg-[#96c464] text-gray-900 font-semibold py-3 px-8 rounded-full shadow-md transition-all transform hover:scale-105">
                {{ __('messages.save_selection') }}
            </button>
        </div>

        @if ($errors->has('seleccion'))
            <div class="mt-4 text-red-600 text-center">
                @foreach ($errors->get('seleccion') as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

    </form>

    @if (session()->has('message'))
        <div class="text-green-600 text-center mt-4">
            {{ session('message') }}
        </div>
    @endif
</div>
