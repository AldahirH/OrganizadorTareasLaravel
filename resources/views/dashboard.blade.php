<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyectos | Nuestros proyectos</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="app-page">
    <main class="workspace" data-project-workspace>
        <header class="workspace-header">
            <div>
                <h1>Proyectos</h1>
                <p>Organiza lo que están construyendo</p>
            </div>

            <div class="user-menu">
                <span>Hola, {{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="button button--ghost" type="submit">Cerrar sesión</button>
                </form>
            </div>
        </header>

        <section class="app-card view-panel" data-view="projects">
            <div class="panel-topline">Nuestros proyectos</div>

            <div class="section-heading">
                <div>
                    <h2>Proyectos</h2>
                    <p>Organiza lo que están construyendo</p>
                </div>
                <button class="button button--primary" type="button" data-action="new-project">
                    <svg class="ui-icon" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 5v14" />
                        <path d="M5 12h14" />
                    </svg>
                    Nuevo proyecto
                </button>
            </div>

            <div class="projects-grid" data-project-list></div>
        </section>

        <section class="app-card view-panel is-hidden" data-view="project-detail">
            <div class="panel-topline">Nuestros proyectos</div>

            <nav class="breadcrumb" aria-label="Ruta">
                <button type="button" data-action="back-projects">Proyectos</button>
                <span>/</span>
                <span data-project-breadcrumb></span>
            </nav>

            <div class="section-heading">
                <div>
                    <h2 data-detail-title></h2>
                    <p data-detail-description></p>
                </div>
                <div class="actions-row">
                    <button class="button button--outline" type="button" data-action="edit-project">
                        <svg class="ui-icon" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 20h9" />
                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z" />
                        </svg>
                        Editar proyecto
                    </button>
                    <button class="button button--primary" type="button" data-action="new-task">
                        <svg class="ui-icon" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 5v14" />
                            <path d="M5 12h14" />
                        </svg>
                        Nueva tarea
                    </button>
                </div>
            </div>

            <div class="filter-tabs" role="tablist" aria-label="Filtrar tareas">
                <button class="filter-tab is-active" type="button" data-filter="all">Todas</button>
                <button class="filter-tab" type="button" data-filter="pending">Pendiente</button>
                <button class="filter-tab" type="button" data-filter="in_progress">En progreso</button>
                <button class="filter-tab" type="button" data-filter="completed">Terminada</button>
            </div>

            <div class="tasks-shell" data-task-shell></div>
        </section>

        <section class="app-card form-panel is-hidden" data-view="project-form">
            <div class="panel-topline">Nuestros proyectos</div>

            <form class="form-stack" data-project-form novalidate>
                <input type="hidden" name="project_id" data-project-id>

                <div class="section-heading section-heading--compact">
                    <div>
                        <h2 data-project-form-title>Nuevo proyecto</h2>
                        <p data-project-form-description>Crea un nuevo proyecto para organizar el trabajo.</p>
                    </div>
                </div>

                <div class="field">
                    <label for="project_name">Nombre</label>
                    <input id="project_name" name="name" type="text" maxlength="80" placeholder="Ej. Sitio web, App móvil, etc." required>
                    <p class="field-error is-hidden" role="alert" data-error-for="project_name"></p>
                </div>

                <div class="field">
                    <label for="project_description">Descripción</label>
                    <textarea id="project_description" name="description" rows="4" maxlength="240" placeholder="Describe brevemente de qué se trata este proyecto..."></textarea>
                    <p class="field-hint"><span data-project-count>0</span>/240 caracteres</p>
                </div>

                <div class="form-actions">
                    <button class="button button--outline" type="button" data-action="cancel-project-form">Cancelar</button>
                    <button class="button button--primary" type="submit" data-project-submit>Crear proyecto</button>
                </div>
            </form>
        </section>

        <section class="app-card form-panel is-hidden" data-view="task-form">
            <div class="panel-topline">Nuestros proyectos</div>

            <nav class="breadcrumb" aria-label="Ruta">
                <button type="button" data-action="back-detail">Proyectos</button>
                <span>/</span>
                <span data-task-project-name></span>
            </nav>

            <form class="form-stack" data-task-form novalidate>
                <input type="hidden" name="task_id" data-task-id>

                <div class="section-heading section-heading--compact">
                    <div>
                        <h2 data-task-form-title>Nueva tarea</h2>
                        <p data-task-form-description>Agrega una nueva tarea a este proyecto.</p>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="field">
                        <label for="task_title">Título</label>
                        <input id="task_title" name="title" type="text" maxlength="90" required>
                        <p class="field-error is-hidden" role="alert" data-error-for="task_title"></p>
                    </div>

                    <div class="field">
                        <label for="task_responsible">Responsable</label>
                        <input id="task_responsible" name="responsible" type="text" maxlength="80" placeholder="Ej. Alex, Dani, etc.">
                    </div>

                    <div class="field">
                        <label for="task_status">Estado</label>
                        <select id="task_status" name="status">
                            <option value="pending">Pendiente</option>
                            <option value="in_progress">En progreso</option>
                            <option value="completed">Terminada</option>
                        </select>
                    </div>

                    <div class="field">
                        <label for="task_due_date">Fecha límite</label>
                        <input id="task_due_date" name="due_date" type="date">
                    </div>
                </div>

                <div class="form-actions">
                    <button class="button button--outline" type="button" data-action="cancel-task-form">Cancelar</button>
                    <button class="button button--primary" type="submit" data-task-submit>Crear tarea</button>
                </div>
            </form>
        </section>

        <div class="modal-backdrop is-hidden" data-delete-modal aria-hidden="true">
            <section class="confirm-modal" role="dialog" aria-modal="true" aria-labelledby="delete-title">
                <button class="modal-close" type="button" data-action="cancel-delete" aria-label="Cerrar">×</button>
                <div class="danger-icon" aria-hidden="true">
                    <svg class="ui-icon" viewBox="0 0 24 24">
                        <path d="M3 6h18" />
                        <path d="M8 6V4h8v2" />
                        <path d="M19 6l-1 14H6L5 6" />
                        <path d="M10 11v5" />
                        <path d="M14 11v5" />
                    </svg>
                </div>
                <h2 id="delete-title">¿Eliminar esta tarea?</h2>
                <p>
                    Se eliminará "<span data-delete-task-name></span>". Esta acción no se puede deshacer.
                </p>
                <div class="form-actions">
                    <button class="button button--outline" type="button" data-action="cancel-delete">Cancelar</button>
                    <button class="button button--danger" type="button" data-action="confirm-delete">Eliminar tarea</button>
                </div>
            </section>
        </div>

        <p class="toast is-hidden" data-toast role="status"></p>
    </main>
</body>
</html>
