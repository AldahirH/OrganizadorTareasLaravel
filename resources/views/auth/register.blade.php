<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta</title>
</head>
<body>
    <main>
        <h1>Crear cuenta</h1>

        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            <div>
                <label for="name">Nombre</label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name') }}"
                    autocomplete="name"
                    maxlength="255"
                    required
                >
                @error('name')
                    <p role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email">Correo electrónico</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    autocomplete="email"
                    maxlength="255"
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
                    autocomplete="new-password"
                    minlength="8"
                    maxlength="72"
                    required
                >
                @error('password')
                    <p role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation">Confirmar contraseña</label>
                <input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    autocomplete="new-password"
                    minlength="8"
                    maxlength="72"
                    required
                >
            </div>

            <button type="submit">Crear cuenta</button>
        </form>

        <p>
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}">Inicia sesión</a>
        </p>
    </main>
</body>
</html>