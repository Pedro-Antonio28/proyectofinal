<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dieta semanal</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { text-align: center; margin-bottom: 20px; }
        h2 { margin-top: 30px; border-bottom: 1px solid #ccc; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; }
    </style>
</head>
<body>
<h1>🗓️ Dieta Semanal</h1>

@foreach ($dieta as $dia => $comidas)
    <h2>{{ ucfirst($dia) }}</h2>
    @foreach (['Desayuno', 'Almuerzo', 'Comida', 'Merienda', 'Cena'] as $tipoComida)
        @if (isset($comidas[$tipoComida]) && count($comidas[$tipoComida]) > 0)
            <h3>{{ $tipoComida }}</h3>
            <table>
                <thead>
                <tr>
                    <th>Alimento</th>
                    <th>Cantidad (g)</th>
                    <th>Calorías</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($comidas[$tipoComida] as $item)
                    <tr>
                        <td>{{ $item['nombre'] }}</td>
                        <td>{{ $item['cantidad'] }}</td>
                        <td>{{ $item['calorias'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    @endforeach
@endforeach
</body>
</html>
