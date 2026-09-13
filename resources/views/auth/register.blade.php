<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta | Nuestros proyectos</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-page">
    <main class="auth-shell auth-shell--register">
        <section class="auth-panel auth-panel--intro" aria-label="Presentación">
            <p class="eyebrow">Nuestros proyectos</p>
            <h1>Tu espacio privado para proyectos y tareas</h1>
            <p>
                Cada cuenta ve solamente sus propios proyectos. El responsable de una tarea
                es texto libre y no comparte acceso con otras personas.
            </p>

            <div class="auth-preview auth-preview--stack" aria-hidden="true">
                <div class="mini-task mini-task--blue"></div>
                <div class="mini-task mini-task--gray"></div>
                <div class="mini-task mini-task--green"></div>
            </div>
        </section>

        <section class="auth-panel">
            <div class="panel-topline">Nuestros proyectos</div>

            <div class="auth-heading">
                <div>
                    <h2>Crear cuenta</h2>
                    <p>Regístrate para empezar a organizar tu trabajo.</p>
                </div>
            </div>

            <form class="form-stack" method="POST" action="{{ route('register.store') }}">
                @csrf

                <div class="field">
                    <label for="name">Nombre</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name') }}"
                        autocomplete="name"
                        maxlength="255"
                        placeholder="Tu nombre"
                        required
                    >
                    @error('name')
                        <p class="field-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="email">Correo electrónico</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        maxlength="255"
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
                            autocomplete="new-password"
                            minlength="8"
                            maxlength="72"
                            placeholder="Mínimo 8 caracteres"
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

                <div class="field">
                    <label for="password_confirmation">Confirmar contraseña</label>
                    <div class="password-field">
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            minlength="8"
                            maxlength="72"
                            placeholder="Repite tu contraseña"
                            required
                        >
                        <button class="icon-button" type="button" data-toggle-password aria-label="Mostrar contraseña">
                            <span data-password-icon>Ver</span>
                        </button>
                    </div>
                </div>

                <button class="button button--primary" type="submit">Crear cuenta</button>
            </form>

            <p class="auth-switch">
                ¿Ya tienes cuenta?
                <a href="{{ route('login') }}">Inicia sesión</a>
            </p>
        </section>
    </main>
</body>
</html>
