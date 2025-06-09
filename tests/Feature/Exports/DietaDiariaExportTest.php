<?php

use App\Exports\DietaDiariaExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\View;

test('DietaDiariaExport generates correct view', function () {
    $comidas = [
        'Desayuno' => [
            ['nombre' => 'Avena', 'cantidad' => 100, 'calorias' => 380],
        ],
        'Comida' => [
            ['nombre' => 'Pollo', 'cantidad' => 150, 'calorias' => 240],
        ],
    ];

    $dia = 'Lunes';

    $export = new DietaDiariaExport($comidas, $dia);

    $view = $export->view();

    expect($view->name())->toBe('exports.dieta-diaria');
    expect($view->getData()['comidas'])->toBe($comidas);
    expect($view->getData()['dia'])->toBe($dia);
});

test('DietaDiariaExport can be downloaded as Excel', function () {
    Excel::fake();

    $comidas = [
        'Cena' => [
            ['nombre' => 'Ensalada', 'cantidad' => 200, 'calorias' => 120],
        ]
    ];
    $dia = 'Martes';

    $export = new \App\Exports\DietaDiariaExport($comidas, $dia);

    Excel::download($export, 'dieta_martes.xlsx');

    Excel::assertDownloaded('dieta_martes.xlsx', function ($exported) use ($comidas, $dia) {
        return $exported instanceof \App\Exports\DietaDiariaExport
            && $exported->dia === $dia
            && $exported->comidas === $comidas;
    });
});
