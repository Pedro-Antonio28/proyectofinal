<table>
    <thead>
    <tr>
        <th>Día</th>
        <th>Tipo de comida</th>
        <th>Alimento</th>
        <th>Cantidad (g)</th>
        <th>Calorías</th>
        <th>Proteínas (g)</th>
        <th>Carbohidratos (g)</th>
        <th>Grasas (g)</th>
    </tr>
    </thead>
    <tbody>
    @foreach (['Desayuno', 'Almuerzo', 'Comida', 'Merienda', 'Cena'] as $tipo)
        @if (isset($comidas[$tipo]))
            @foreach ($comidas[$tipo] as $alimento)
                <tr>
                    <td>{{ $dia }}</td>
                    <td>{{ $tipo }}</td>
                    <td>{{ $alimento['nombre'] }}</td>
                    <td>{{ $alimento['cantidad'] }}</td>
                    <td>{{ $alimento['calorias'] }}</td>
                    <td>{{ $alimento['proteinas'] ?? 0 }}</td>
                    <td>{{ $alimento['carbohidratos'] ?? 0 }}</td>
                    <td>{{ $alimento['grasas'] ?? 0 }}</td>
                </tr>
            @endforeach
        @endif
    @endforeach
    </tbody>
</table>
