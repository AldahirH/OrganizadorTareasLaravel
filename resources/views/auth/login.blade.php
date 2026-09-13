<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión | Nuestros proyectos</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-page">
    <main class="auth-shell">
        <section class="auth-panel auth-panel--intro" aria-label="Presentación">
            <p class="eyebrow">Nuestros proyectos</p>
            <h1>Organiza lo que están construyendo</h1>
            <p>
                Administra proyectos privados, tareas y avances desde una interfaz sencilla,
                clara y preparada para conectarse con Laravel.
            </p>

            <div class="auth-preview" aria-hidden="true">
                <div class="preview-card">
                    <span></span>
                    <strong>Portafolio web</strong>
                    <small>3 tareas</small>
                </div>
                <div class="preview-card">
                    <span></span>
                    <strong>Gestor de tareas</strong>
                    <small>5 tareas</small>
                </div>
            </div>
        </section>

        <section class="auth-panel">
            <div class="panel-topline">Nuestros proyectos</div>

            <div class="auth-heading">
                <div>
                    <h2>Iniciar sesión</h2>
                    <p>Entra para continuar con tus proyectos.</p>
                </div>
            </div>

            @if (session('status'))
                <p class="flash flash--success" role="status">{{ session('status') }}</p>
            @endif

            <form class="form-stack" method="POST" action="{{ route('login.store') }}">
                @csrf

                <div class="field">
                    <label for="email">Correo electrónico</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        autocomplete="username"
                        placeholder="tu@email.com"
                        required
                    >
                    @error('email')
                        <p class="field-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="password">Contraseña</label>
                    <div class="password-field">
                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            placeholder="Tu contraseña"
                            required
                        >
                        <button class="icon-button" type="button" data-toggle-password aria-label="Mostrar contraseña">
                            <span data-password-icon>Ver</span>
                        </button>
                    </div>
                    @error('password')
                        <p class="field-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <button class="button button--primary" type="submit">Entrar</button>
            </form>

            <p class="auth-switch">
                ¿No tienes cuenta?
                <a href="{{ route('register') }}">Regístrate</a>
            </p>
        </section>
    </main>
</body>
</html>
