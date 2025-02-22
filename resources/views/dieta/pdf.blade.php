<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dieta para {{ ucfirst($dia) }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { text-align: center; }
        .comida { margin-bottom: 20px; }
        .comida h2 { border-bottom: 1px solid #ccc; padding-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
    </style>
</head>
<body>
<h1>Dieta para {{ ucfirst($dia) }}</h1>
@foreach (['Desayuno', 'Almuerzo', 'Comida', 'Merienda', 'Cena'] as $tipoComida)
    @if(isset($comidas[$tipoComida]) && count($comidas[$tipoComida]) > 0)
        <div class="comida">
            <h2>{{ $tipoComida }}</h2>
            <table>
                <thead>
                <tr>
                    <th>Alimento</th>
                    <th>Cantidad (g)</th>
                    <th>Calorías</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($comidas[$tipoComida] as $alimento)
                    <tr>
                        <td>{{ $alimento['nombre'] }}</td>
                        <td>{{ $alimento['cantidad'] }}</td>
                        <td>{{ $alimento['calorias'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endforeach
</body>
</html>
