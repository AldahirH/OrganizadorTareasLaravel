<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
</head>
<body>
    <main>
        <h1>Iniciar sesión</h1>

        @if (session('status'))
            <p role="status">{{ session('status') }}</p>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            <div>
                <label for="email">Correo electrónico</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    autocomplete="username"
                    required
                >
                @error('email')
                    <p role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password">Contraseña</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    autocomplete="current-password"
                    required
                >
                @error('password')
                    <p role="alert">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit">Entrar</button>
        </form>

        <p>
            ¿No tienes cuenta?
            <a href="{{ route('register') }}">Regístrate</a>
        </p>
    </main>
</body>
</html>