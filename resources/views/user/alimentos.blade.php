@extends('layouts.public')

@section('content')
    <h1 class="text-4xl font-bold text-gray-900 text-center mb-8">Selecciona tus alimentos favoritos</h1>

    <form action="{{ route('user.alimentos.store') }}" method="POST" class="space-y-8">
        @csrf

        @php
            $categorias = [
                'proteinas' => 'Proteínas',
                'carbohidratos' => 'Carbohidratos',
                'frutas' => 'Frutas',
                'verduras' => 'Verduras'
            ];
        @endphp

        @foreach($categorias as $key => $nombre)
            <h2 class="text-2xl font-semibold text-gray-900 border-b-2 border-[#a7d675] pb-2">{{ $nombre }}</h2>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mt-4 justify-center">
                @foreach($alimentos->where('categoria', $key) as $alimento)
                    <label class="relative group cursor-pointer transition transform hover:scale-105 flex flex-col items-center w-40 mx-auto">

                        <input type="checkbox" name="alimentos[]" value="{{ $alimento->id }}" class="hidden peer"
                            {{ in_array($alimento->id, $favoritos) ? 'checked' : '' }}>

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
                                <img src="{{ asset('images/default-food.png') }}" alt="Imagen no disponible"
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
                Guardar selección
            </button>
        </div>
    </form>
@endsection
