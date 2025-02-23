<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Correo</title>
</head>
<body>
<h2>Hola, {{ $user->name }}</h2>
<p>Gracias por registrarte. Por favor, haz clic en el siguiente enlace para verificar tu cuenta:</p>
<p><a href="{{ $url }}">Verificar mi correo</a></p>
</body>
</html>
