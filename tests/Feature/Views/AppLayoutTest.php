<?php

use Tests\TestCase;
use App\View\Components\AppLayout;
use Illuminate\Support\Facades\View;

 // ✅ Asegura que esta línea esté presente

it('renders the layout correctly', function () {
    $component = new AppLayout();

    expect($component->render()->name())->toBe('layouts.CalorixLayout');
});

