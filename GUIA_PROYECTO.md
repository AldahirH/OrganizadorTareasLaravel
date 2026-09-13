# Organizador de proyectos y tareas — guía compartida

## Para compañeros y asistentes de IA

Lee esta guía y `AGENTS.md` antes de trabajar. Esta guía describe el alcance acordado y la propuesta de integración; no demuestra que las funcionalidades estén implementadas. Inspecciona el código y las rutas reales antes de usarlas. Actualiza esta guía cuando el equipo acuerde cambios.

El responsable del backend está aprendiendo Laravel: explica cada paso, el archivo involucrado, su propósito y cómo verificarlo. Trabaja en bloques pequeños.

## 1. Alcance confirmado

- Organizador de proyectos y tareas con registro, inicio y cierre de sesión.
- **Opción A: proyectos privados por usuario.** Cada cuenta ve y administra únicamente sus proyectos y las tareas de esos proyectos.
- Frontend con HTML, CSS y JavaScript vanilla.
- Interfaz en español, basada en las maquetas compartidas por el equipo.
- El responsable de una tarea es texto libre; escribir el nombre de otra persona no comparte el proyecto ni le concede acceso.
- La primera versión no contempla miembros, invitaciones, roles de equipo ni proyectos compartidos.

## 2. Arquitectura propuesta

- Backend: Laravel; consultar las versiones instaladas antes de usar APIs del framework.
- Base de datos local: SQLite.
- Servir las pantallas desde Laravel usando vistas `.blade.php`: contienen HTML normal y directivas para datos, rutas, validación y CSRF.
- Mantener CSS y JavaScript vanilla; no añadir frameworks o dependencias sin acuerdo del equipo.
- Autenticación mediante sesiones y cookies de Laravel en el mismo origen.
- Formularios tradicionales para las operaciones iniciales. Usar `fetch()` cuando sea útil, por ejemplo para cambiar el estado sin recargar.
- No se necesita una API separada ni guardar tokens de acceso en `localStorage` para esta arquitectura.

### Ubicaciones

| Ubicación | Responsabilidad |
| --- | --- |
| `routes/web.php` | Rutas de páginas y acciones con sesión |
| `app/Http/Controllers/` | Coordinar solicitudes y respuestas |
| `app/Http/Requests/` | Validación de entradas |
| `app/Models/` | Datos y relaciones con Eloquent |
| `app/Policies/` | Permisos de acceso a los recursos |
| `database/migrations/` | Estructura reproducible de las tablas |
| `database/factories/`, `database/seeders/` | Datos de prueba o demostración |
| `resources/views/` | Pantallas y componentes Blade |
| `resources/css/`, `resources/js/` | CSS y JavaScript |
| `tests/Feature/` | Pruebas de solicitudes, validación y permisos |

## 3. Pantallas de referencia

Las imágenes se compartieron en la conversación; esta guía no presupone que estén guardadas en el repositorio.

1. **Registro:** nombre, correo, contraseña y confirmación; enlace al login.
2. **Inicio de sesión:** correo y contraseña; enlace al registro.
3. **Proyectos:** tarjetas con nombre, descripción, cantidad de tareas, ver y editar; botón de nuevo proyecto y menú de usuario.
4. **Detalle del proyecto:** datos del proyecto, tabla de tareas, filtros y botones de edición y creación.
5. **Nuevo proyecto:** nombre y descripción.
6. **Editar proyecto:** modificar nombre y descripción.
7. **Nueva tarea:** título, responsable, estado y fecha límite; errores junto a los campos.
8. **Editar tarea:** los mismos campos con valores actuales.
9. **Eliminar tarea:** confirmación antes de solicitar el borrado al backend.
10. **Proyecto sin tareas:** estado vacío con botón para agregar una tarea.
11. **Sesión cerrada:** login con mensaje de cierre exitoso; no necesita una página independiente.
12. **Acceso protegido:** un visitante sin sesión es enviado al login; puede mostrarse allí el mensaje de acceso requerido.

El botón de mostrar contraseña, las ventanas de confirmación y los mensajes visuales corresponden al frontend. Las validaciones, los permisos y los cambios persistentes corresponden al backend.

## 4. Modelo de datos propuesto

Revisar las migraciones existentes antes de crear tablas, especialmente `users`, que suele venir con Laravel.

| Tabla | Campos principales |
| --- | --- |
| `users` | `id`, `name`, `email`, `password`, fechas de creación y actualización |
| `projects` | `id`, `user_id`, `name`, `description`, fechas de creación y actualización |
| `tasks` | `id`, `project_id`, `title`, `responsible`, `status`, `due_date`, fechas de creación y actualización |

Relaciones: un usuario tiene muchos proyectos; un proyecto pertenece a un usuario y tiene muchas tareas; una tarea pertenece a un proyecto.

### Contrato inicial propuesto para los campos

- Registro: `name`, `email`, `password`, `password_confirmation`.
- Proyecto: `name`, `description`.
- Tarea: `title`, `responsible`, `status`, `due_date`.
- Estados internos propuestos: `pending`, `in_progress`, `completed`; etiquetas: Pendiente, En progreso, Terminada.
- Fecha enviada: `YYYY-MM-DD`; la interfaz puede mostrarla con formato local.
- El título de la tarea es obligatorio según la maqueta. Antes de implementar, acordar los demás campos obligatorios, longitudes máximas y reglas de fechas y contraseñas.
- Las rutas concretas, sus nombres y los formatos JSON deben definirse y documentarse al implementarlos. No tratarlos como existentes todavía.

## 5. Autenticación y privacidad

- Guardar contraseñas mediante el hash de Laravel, nunca como texto plano.
- Validar registro y credenciales en el servidor y limitar intentos de login.
- Regenerar la sesión después de autenticar; al salir, cerrar la sesión, invalidarla y regenerar el token CSRF.
- Proteger las rutas de proyectos y tareas con autenticación y autorización de propiedad.
- Derivar `user_id` del usuario autenticado; no confiar en un dueño enviado por el navegador.
- Comprobar que el proyecto pertenece al usuario para consultar o modificar sus tareas. Si una ruta incluye proyecto y tarea, comprobar también que esa tarea pertenece a ese proyecto.
- Filtrar también los listados y conteos: no exponer datos de otras cuentas.
- Ocultar botones en HTML no reemplaza los permisos del backend.
- Usar CSRF en formularios de escritura y en las solicitudes `fetch()` correspondientes; no desactivar esa protección para facilitar la integración.
- Mostrar los textos del usuario escapados; evitar insertar esos valores con `innerHTML` o salida Blade sin escape.

## 6. Acuerdo de integración frontend/backend

1. El frontend crea las pantallas y componentes visuales con los campos acordados.
2. El backend define rutas nombradas, validaciones, permisos y respuestas.
3. Integrar el HTML en Blade y generar direcciones mediante `route()`.
4. Formularios: añadir `@csrf` y, cuando corresponda, `@method(...)`; mostrar errores y recuperar entradas anteriores salvo contraseñas.
5. Para `fetch()`, acordar el contrato antes de implementarlo: URL generada, método, campos, respuesta exitosa y errores. Solicitar JSON cuando se espere JSON y manejar validación, sesión expirada y errores de servidor.
6. Los mensajes de éxito aparecen únicamente después de que el backend confirme la operación.
7. Los filtros se aplican a las tareas del proyecto autorizado. Distinguir proyecto sin tareas de filtro sin resultados.

## 7. SQLite y Git

- `.env` y el archivo SQLite local están excluidos del repositorio por los archivos `.gitignore` actuales. Las migraciones sí se comparten.
- `.env.example` es la plantilla compartida y no debe contener credenciales reales.
- Con SQLite, `DB_DATABASE` representa una ruta de archivo, no un nombre de base de datos MySQL.
- La configuración existente permite usar `database/database.sqlite` dejando `DB_CONNECTION=sqlite` y sin definir `DB_DATABASE`.
- En una revisión anterior apareció `DB_DATABASE=laravel` en `.env`: revisar y corregir si sigue presente. No asumir que la conexión funciona solo porque existe un archivo SQLite.
- Después de ajustar la configuración, limpiar la caché con `php artisan config:clear` y comprobar migraciones con `php artisan migrate:status`. Ejecutar las migraciones pendientes con `php artisan migrate` cuando corresponda.
- Subir código a GitHub no publica la aplicación como sitio web ni elimina el `.env` o la clave local.

## 8. Plan de implementación

Esta lista es una hoja de ruta, no una certificación del estado actual del código.

1. Verificar configuración y conexión real a SQLite.
2. Implementar y probar registro, login, logout y acceso protegido.
3. Crear migraciones y modelos de proyectos y tareas con sus relaciones.
4. Implementar proyectos privados: listar, crear, consultar y editar.
5. Implementar tareas: crear, editar, cambiar estado, filtrar y eliminar.
6. Integrar las pantallas y sus estados vacíos, errores y mensajes de éxito.
7. Probar que una cuenta no puede consultar ni modificar recursos de otra, incluyendo tareas, filtros y conteos.

## 9. Cómo pedir ayuda a una IA

Puedes copiar este mensaje:

> Lee AGENTS.md y GUIA_PROYECTO.md en la raíz del repositorio antes de trabajar. Estamos construyendo un organizador en Laravel con SQLite, proyectos privados por usuario y frontend HTML/CSS/JS vanilla integrado en Blade. Inspecciona qué existe y distingue lo implementado de lo propuesto en la guía. Explícame los cambios paso a paso y respeta el contrato acordado con el otro integrante. Mi tarea actual es: [describe aquí la tarea].
