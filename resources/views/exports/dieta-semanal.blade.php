<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dieta semanal</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            background-color: #f6f8fa;
            color: #333;
        }

        h1 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 30px;
        }

        h2 {
            margin-top: 40px;
            font-size: 18px;
            background-color: #d5e8d4;
            padding: 6px;
            border-left: 5px solid #4CAF50;
        }

        h3 {
            margin-top: 20px;
            font-size: 15px;
            color: #2e7d32;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            margin-bottom: 20px;
        }

        th {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }

        .resumen {
            margin-top: 50px;
            background-color: #fffde7;
            padding: 10px;
            border: 1px solid #fbc02d;
        }

        .barra-label {
            margin-top: 6px;
            font-weight: bold;
        }

        .barra {
            height: 14px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<h1>Dieta Semanal Personalizada</h1>

@php
    $totalCalorias = 0;
    $totalProteinas = 0;
    $totalCarbs = 0;
    $totalGrasas = 0;
    $ordenDias = ['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'];
@endphp

@foreach ($ordenDias as $dia)
    @if(isset($dieta[$dia]))
        @php
            $comidas = $dieta[$dia];
            $caloriasDia = 0;
        @endphp

        <h2>{{ $dia }}</h2>

        @foreach (['Desayuno', 'Almuerzo', 'Comida', 'Merienda', 'Cena'] as $tipoComida)
            @if (!empty($comidas[$tipoComida]))
                <h3>{{ $tipoComida }}</h3>
                <table>
                    <thead>
                    <tr>
                        <th>Alimento</th>
                        <th>Cantidad (g)</th>
                        <th>Calorías</th>
                        <th>Proteínas (g)</th>
                        <th>Carbohidratos (g)</th>
                        <th>Grasas (g)</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($comidas[$tipoComida] as $item)
                        <tr>
                            <td>{{ $item['nombre'] }}</td>
                            <td>{{ $item['cantidad'] }}</td>
                            <td>{{ $item['calorias'] }}</td>
                            <td>{{ $item['proteinas'] ?? 0 }}</td>
                            <td>{{ $item['carbohidratos'] ?? 0 }}</td>
                            <td>{{ $item['grasas'] ?? 0 }}</td>
                        </tr>
                        @php
                            $caloriasDia += $item['calorias'];
                            $totalCalorias += $item['calorias'];
                            $totalProteinas += $item['proteinas'] ?? 0;
                            $totalCarbs += $item['carbohidratos'] ?? 0;
                            $totalGrasas += $item['grasas'] ?? 0;
                        @endphp
                    @endforeach
                    </tbody>
                </table>
            @endif
        @endforeach

        <p><strong>Total del día:</strong> {{ $caloriasDia }} kcal</p>
    @endif
@endforeach

<div class="resumen">
    <h2>Resumen nutricional semanal</h2>
    <p><strong>Calorías totales:</strong> {{ $totalCalorias }} kcal</p>
    <p><strong>Proteínas:</strong> {{ $totalProteinas }} g</p>
    <p><strong>Carbohidratos:</strong> {{ $totalCarbs }} g</p>
    <p><strong>Grasas:</strong> {{ $totalGrasas }} g</p>

    <div class="barra-label">Proteínas</div>
    <div class="barra" style="width: {{ $totalProteinas * 0.5 }}px; background-color: #81c784;"></div>

    <div class="barra-label">Carbohidratos</div>
    <div class="barra" style="width: {{ $totalCarbs * 0.5 }}px; background-color: #4fc3f7;"></div>

    <div class="barra-label">Grasas</div>
    <div class="barra" style="width: {{ $totalGrasas * 0.5 }}px; background-color: #ffb74d;"></div>
</div>

</body>
</html>
