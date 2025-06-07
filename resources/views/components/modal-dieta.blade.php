<div x-show="abierto" x-cloak class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md space-y-4 shadow-xl">
        <h2 class="text-xl font-bold text-center">¿Dónde quieres añadir esta receta?</h2>

        {{-- Día --}}
        <div>
            <label class="block text-sm font-semibold">Día:</label>
            <select name="dia" class="w-full mt-1 border rounded p-2">
                @foreach(['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'] as $dia)
                    <option value="{{ $dia }}">{{ $dia }}</option>
                @endforeach
            </select>
        </div>

        {{-- Tipo de comida --}}
        <div>
            <label class="block text-sm font-semibold">Tipo de comida:</label>
            <select name="tipo_comida" class="w-full mt-1 border rounded p-2">
                @foreach(['Desayuno','Comida','Merienda','Cena'] as $tipo)
                    <option value="{{ $tipo }}">{{ $tipo }}</option>
                @endforeach
            </select>
        </div>

        {{-- Cantidad --}}
        <div>
            <label class="block text-sm font-semibold">Cantidad (en gramos):</label>
            <input type="number" min="1" name="cantidad" placeholder="Ej: 150"
                   class="w-full mt-1 border rounded p-2" />
        </div>

        {{-- Botones --}}
        <div class="flex justify-end gap-2 pt-4">
            <button type="button" @click="abierto = false" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Cancelar</button>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Añadir</button>
        </div>
    </div>
</div>
