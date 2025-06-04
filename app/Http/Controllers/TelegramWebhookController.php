<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TelegramUser;
use App\Models\User;

class TelegramWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $data = $request->all();

        // Si no hay mensaje, salimos
        if (!isset($data['message'])) return response('ok', 200);

        $message = $data['message'];
        $chatId = $message['chat']['id'];
        $text = $message['text'] ?? '';

        // Si viene con /start 123 (donde 123 es el user_id)
        if (str_starts_with($text, '/start')) {
            $parts = explode(' ', $text);

            if (isset($parts[1])) {
                $userId = (int)$parts[1];

                // Guardar o actualizar
                TelegramUser::updateOrCreate(
                    ['user_id' => $userId],
                    ['telegram_id' => $chatId]
                );

                // Responder al usuario
                $this->sendMessage($chatId, "✅ Tu cuenta ha sido vinculada correctamente.");
            }
        }

        return response('ok', 200);
    }

    private function sendMessage($chatId, $text)
    {
        $token = env('TELEGRAM_BOT_TOKEN');

        \Illuminate\Support\Facades\Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $text,
        ]);
    }
}

