@component('mail::message')
    # ¡Hola {{ $user->name }}! 🥗

    Aquí tienes tu dieta **semanal** en formato PDF como adjunto.

    Gracias por usar {{ config('app.name') }} 💚

@endcomponent
