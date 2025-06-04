<?php

namespace App\Listeners;

use App\Events\DietaSolicitada;
use App\Models\Dieta;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class EnviarDietaPorTelegram
{
    public function handle(DietaSolicitada $event)
    {
        $user = $event->user;
        $telegramId = $user->telegram?->telegram_id;

        if (!$telegramId) return;

        $dieta = Dieta::deSemanaActual($user->id)->first();
        if (!$dieta) return;

        $dia = ucfirst(Carbon::now()->locale('es')->isoFormat('dddd'));
        $dietaData = json_decode($dieta->dieta, true)[$dia] ?? [];

        if (!$dietaData) return;

        $mensaje = "🍽 Tu dieta para hoy ($dia)\n\n";
        $ordenComidas = ['Desayuno', 'Almuerzo', 'Comida', 'Merienda', 'Cena'];

        foreach ($ordenComidas as $tipo) {
            if (!isset($dietaData[$tipo])) continue;

            $mensaje .= "🍴 $tipo\n";
            foreach ($dietaData[$tipo] as $al) {
                $mensaje .= "- {$al['cantidad']}g {$al['nombre']}\n";
            }
            $mensaje .= "\n";
        }


        Http::post("https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage", [
            'chat_id' => $telegramId,
            'text' => $mensaje,
        ]);
    }
}
