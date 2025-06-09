<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Mockery;


class PayPalController extends Controller
{
    public function createTransaction()
    {
        $paypal = new PayPalClient;
        $paypal->setApiCredentials(config('paypal'));
        $token = $paypal->getAccessToken();
        $paypal->setAccessToken($token);

        $order = $paypal->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypal.success'),
                "cancel_url" => route('paypal.cancel'),
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => config('paypal.currency'),
                        "value" => "4.99" // Precio del plan premium
                    ],
                    "description" => "Acceso a funciones premium de dieta personalizada"
                ]
            ]
        ]);

        foreach ($order['links'] as $link) {
            if ($link['rel'] === 'approve') {
                return redirect()->away($link['href']);
            }
        }

        return redirect()->route('dashboard')->with('error', 'Error al iniciar la transacción con PayPal.');
    }

    public function successTransaction(Request $request)
    {
        $paypal = new PayPalClient;
        $paypal->setApiCredentials(config('paypal'));
        $token = $paypal->getAccessToken();
        $paypal->setAccessToken($token);

        $result = $paypal->capturePaymentOrder($request->token);

        if (isset($result['status']) && $result['status'] === 'COMPLETED') {
            // Actualizar usuario a premium
            $user = auth()->user();
            if ($user) {
                $user->is_premium = true;
                $user->save();
            }

            return redirect()->route('dashboard')->with('success', '¡Gracias por tu compra! Ahora tienes acceso premium.');
        }

        return redirect()->route('dashboard')->with('error', 'El pago no se completó correctamente.');
    }

    public function cancelTransaction()
    {
        return redirect()->route('dashboard')->with('error', 'El pago fue cancelado.');
    }

}
