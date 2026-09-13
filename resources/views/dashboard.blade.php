<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi cuenta</title>
</head>
<body>
    <main>
        <h1>Hola, {{ auth()->user()->name }}</h1>

        <p>Tu sesión está activa.</p>
        <p>Aquí conectaremos tu lista de proyectos.</p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Cerrar sesión</button>
        </form>
    </main>
</body>
</html>