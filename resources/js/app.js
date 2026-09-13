const statusLabels = {
    pending: 'Pendiente',
    in_progress: 'En progreso',
    completed: 'Terminada',
};

const icons = {
    plus: `
        <svg class="ui-icon" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M12 5v14" />
            <path d="M5 12h14" />
        </svg>
    `,
    pencil: `
        <svg class="ui-icon" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M12 20h9" />
            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z" />
        </svg>
    `,
    trash: `
        <svg class="ui-icon" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M3 6h18" />
            <path d="M8 6V4h8v2" />
            <path d="M19 6l-1 14H6L5 6" />
            <path d="M10 11v5" />
            <path d="M14 11v5" />
        </svg>
    `,
    file: `
        <svg class="ui-icon" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z" />
            <path d="M14 2v6h6" />
        </svg>
    `,
};

const dateFormatter = new Intl.DateTimeFormat('es-MX', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
});

function setupPasswordToggles() {
    document.querySelectorAll('[data-toggle-password]').forEach((button) => {
        button.addEventListener('click', () => {
            const field = button.closest('.password-field')?.querySelector('input');
            const label = button.querySelector('[data-password-icon]');

            if (!field || !label) {
                return;
            }

            const shouldShowPassword = field.type === 'password';
            field.type = shouldShowPassword ? 'text' : 'password';
            label.textContent = shouldShowPassword ? 'Ocultar' : 'Ver';
            button.setAttribute('aria-label', shouldShowPassword ? 'Ocultar contraseña' : 'Mostrar contraseña');
        });
    });
}

function setupProjectWorkspace() {
    const workspace = document.querySelector('[data-project-workspace]');

    if (!workspace) {
        return;
    }

    const state = {
        selectedProjectId: 1,
        selectedTaskId: null,
        pendingDeleteTaskId: null,
        taskFilter: 'all',
        projects: [
            {
                id: 1,
                name: 'Portafolio web',
                description: 'Nuestro primer sitio con proyectos y contacto.',
                tasks: [
                    {
                        id: 1,
                        title: 'Diseñar el inicio',
                        responsible: 'Alex',
                        status: 'in_progress',
                        dueDate: '2026-09-18',
                    },
                    {
                        id: 2,
                        title: 'Crear formulario',
                        responsible: 'Dani',
                        status: 'pending',
                        dueDate: '2026-09-20',
                    },
                    {
                        id: 3,
                        title: 'Definir colores',
                        responsible: 'Alex',
                        status: 'completed',
                        dueDate: '2026-09-15',
                    },
                ],
            },
            {
                id: 2,
                name: 'Gestor de tareas',
                description: 'Aplicación para organizar tareas en equipo.',
                tasks: [
                    {
                        id: 4,
                        title: 'Preparar vistas Blade',
                        responsible: 'Feli',
                        status: 'in_progress',
                        dueDate: '2026-09-22',
                    },
                    {
                        id: 5,
                        title: 'Validar formularios',
                        responsible: 'Backend',
                        status: 'pending',
                        dueDate: '',
                    },
                ],
            },
            {
                id: 3,
                name: 'Catálogo de libros',
                description: 'Listado de libros con búsqueda y filtros.',
                tasks: [],
            },
        ],
    };

    const views = workspace.querySelectorAll('[data-view]');
    const projectList = workspace.querySelector('[data-project-list]');
    const taskShell = workspace.querySelector('[data-task-shell]');
    const toast = workspace.querySelector('[data-toast]');
    const deleteModal = workspace.querySelector('[data-delete-modal]');

    const projectForm = workspace.querySelector('[data-project-form]');
    const projectNameInput = workspace.querySelector('#project_name');
    const projectDescriptionInput = workspace.querySelector('#project_description');
    const projectIdInput = workspace.querySelector('[data-project-id]');
    const projectDescriptionCount = workspace.querySelector('[data-project-count]');

    const taskForm = workspace.querySelector('[data-task-form]');
    const taskIdInput = workspace.querySelector('[data-task-id]');
    const taskTitleInput = workspace.querySelector('#task_title');
    const taskResponsibleInput = workspace.querySelector('#task_responsible');
    const taskStatusInput = workspace.querySelector('#task_status');
    const taskDueDateInput = workspace.querySelector('#task_due_date');

    function findProject(projectId = state.selectedProjectId) {
        return state.projects.find((project) => project.id === Number(projectId));
    }

    function findTask(taskId = state.selectedTaskId) {
        return findProject()?.tasks.find((task) => task.id === Number(taskId));
    }

    function showView(viewName) {
        views.forEach((view) => {
            view.classList.toggle('is-hidden', view.dataset.view !== viewName);
        });
    }

    function showToast(message) {
        if (!toast) {
            return;
        }

        toast.textContent = message;
        toast.classList.remove('is-hidden');
        window.clearTimeout(showToast.timeoutId);
        showToast.timeoutId = window.setTimeout(() => {
            toast.classList.add('is-hidden');
        }, 2500);
    }

    function createProjectCard(project) {
        const card = document.createElement('article');
        card.className = 'project-card';
        card.innerHTML = `
            <div class="project-icon" aria-hidden="true">${icons.file}</div>
            <h3></h3>
            <p></p>
            <div class="project-meta">${project.tasks.length} ${project.tasks.length === 1 ? 'tarea' : 'tareas'}</div>
            <div class="project-actions">
                <button class="button button--outline" type="button" data-open-project="${project.id}">Ver proyecto</button>
                <button class="square-button" type="button" data-edit-project="${project.id}" aria-label="Editar proyecto">${icons.pencil}</button>
            </div>
        `;
        card.querySelector('h3').textContent = project.name;
        card.querySelector('p').textContent = project.description || 'Sin descripción todavía.';

        return card;
    }

    function renderProjects() {
        if (!projectList) {
            return;
        }

        projectList.replaceChildren(...state.projects.map(createProjectCard));
    }

    function renderDetail() {
        const project = findProject();

        if (!project || !taskShell) {
            return;
        }

        workspace.querySelector('[data-project-breadcrumb]').textContent = project.name;
        workspace.querySelector('[data-detail-title]').textContent = project.name;
        workspace.querySelector('[data-detail-description]').textContent = project.description || 'Sin descripción todavía.';
        workspace.querySelector('[data-task-project-name]').textContent = project.name;

        const visibleTasks = state.taskFilter === 'all'
            ? project.tasks
            : project.tasks.filter((task) => task.status === state.taskFilter);

        workspace.querySelectorAll('[data-filter]').forEach((button) => {
            button.classList.toggle('is-active', button.dataset.filter === state.taskFilter);
        });

        if (project.tasks.length === 0) {
            taskShell.innerHTML = `
                <div class="empty-state">
                    <div>
                        <div class="empty-icon" aria-hidden="true">${icons.file}</div>
                        <h3>Todavía no hay tareas</h3>
                        <p>Agrega la primera tarea de este proyecto.</p>
                        <button class="button button--primary" type="button" data-action="new-task">${icons.plus} Nueva tarea</button>
                    </div>
                </div>
            `;
            return;
        }

        if (visibleTasks.length === 0) {
            taskShell.innerHTML = `
                <div class="empty-state">
                    <div>
                        <div class="empty-icon" aria-hidden="true">${icons.file}</div>
                        <h3>No hay tareas con este filtro</h3>
                        <p>Prueba con otro estado o agrega una tarea nueva.</p>
                    </div>
                </div>
            `;
            return;
        }

        const rows = visibleTasks.map((task) => `
            <tr>
                <td>${escapeHtml(task.title)}</td>
                <td>${escapeHtml(task.responsible || 'Sin responsable')}</td>
                <td>
                    <select class="status-select" data-status-task="${task.id}" data-status="${task.status}" aria-label="Estado de ${escapeHtml(task.title)}">
                        ${Object.entries(statusLabels).map(([value, label]) => `
                            <option value="${value}" ${task.status === value ? 'selected' : ''}>${label}</option>
                        `).join('')}
                    </select>
                </td>
                <td>${formatDate(task.dueDate)}</td>
                <td>
                    <div class="table-actions">
                        <button class="square-button" type="button" data-edit-task="${task.id}" aria-label="Editar tarea ${escapeHtml(task.title)}">${icons.pencil}</button>
                        <button class="square-button square-button--danger" type="button" data-delete-task="${task.id}" aria-label="Eliminar tarea ${escapeHtml(task.title)}">${icons.trash}</button>
                    </div>
                </td>
            </tr>
        `).join('');

        taskShell.innerHTML = `
            <table class="tasks-table">
                <thead>
                    <tr>
                        <th>Tarea</th>
                        <th>Responsable</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>${rows}</tbody>
            </table>
        `;
    }

    function showProjectDetail(projectId) {
        state.selectedProjectId = Number(projectId);
        state.taskFilter = 'all';
        renderDetail();
        showView('project-detail');
    }

    function openProjectForm(projectId = null) {
        const project = projectId ? findProject(projectId) : null;

        projectForm.reset();
        clearValidation(projectNameInput);
        projectIdInput.value = project?.id || '';
        projectNameInput.value = project?.name || '';
        projectDescriptionInput.value = project?.description || '';
        workspace.querySelector('[data-project-form-title]').textContent = project ? 'Editar proyecto' : 'Nuevo proyecto';
        workspace.querySelector('[data-project-form-description]').textContent = project
            ? 'Actualiza la información de tu proyecto.'
            : 'Crea un nuevo proyecto para organizar el trabajo.';
        workspace.querySelector('[data-project-submit]').textContent = project ? 'Guardar cambios' : 'Crear proyecto';
        updateProjectDescriptionCount();
        showView('project-form');
    }

    function saveProject(event) {
        event.preventDefault();
        const name = projectNameInput.value.trim();
        const description = projectDescriptionInput.value.trim();

        if (!name) {
            showValidation(projectNameInput, 'El nombre del proyecto es obligatorio.');
            return;
        }

        const projectId = projectIdInput.value;

        if (projectId) {
            const project = findProject(projectId);
            project.name = name;
            project.description = description;
            showToast('Proyecto actualizado');
            renderProjects();
            renderDetail();
            showView('project-detail');
            return;
        }

        const newProject = {
            id: nextId(state.projects),
            name,
            description,
            tasks: [],
        };

        state.projects.push(newProject);
        state.selectedProjectId = newProject.id;
        showToast('Proyecto creado');
        renderProjects();
        renderDetail();
        showView('project-detail');
    }

    function openTaskForm(taskId = null) {
        const task = taskId ? findTask(taskId) : null;

        taskForm.reset();
        clearValidation(taskTitleInput);
        taskIdInput.value = task?.id || '';
        taskTitleInput.value = task?.title || '';
        taskResponsibleInput.value = task?.responsible || '';
        taskStatusInput.value = task?.status || 'pending';
        taskDueDateInput.value = task?.dueDate || '';
        workspace.querySelector('[data-task-form-title]').textContent = task ? 'Editar tarea' : 'Nueva tarea';
        workspace.querySelector('[data-task-form-description]').textContent = task
            ? 'Actualiza la información de esta tarea.'
            : 'Agrega una nueva tarea a este proyecto.';
        workspace.querySelector('[data-task-submit]').textContent = task ? 'Guardar cambios' : 'Crear tarea';
        showView('task-form');
    }

    function saveTask(event) {
        event.preventDefault();
        const project = findProject();
        const title = taskTitleInput.value.trim();

        if (!project) {
            return;
        }

        if (!title) {
            showValidation(taskTitleInput, 'El título es obligatorio.');
            return;
        }

        const taskId = taskIdInput.value;
        const taskData = {
            title,
            responsible: taskResponsibleInput.value.trim(),
            status: taskStatusInput.value,
            dueDate: taskDueDateInput.value,
        };

        if (taskId) {
            const task = findTask(taskId);
            Object.assign(task, taskData);
            showToast('Tarea actualizada');
        } else {
            project.tasks.push({
                id: nextId(state.projects.flatMap((item) => item.tasks)),
                ...taskData,
            });
            showToast('Tarea creada');
        }

        renderProjects();
        renderDetail();
        showView('project-detail');
    }

    function openDeleteModal(taskId) {
        const task = findTask(taskId);

        if (!task || !deleteModal) {
            return;
        }

        state.pendingDeleteTaskId = Number(taskId);
        workspace.querySelector('[data-delete-task-name]').textContent = task.title;
        deleteModal.classList.remove('is-hidden');
        deleteModal.setAttribute('aria-hidden', 'false');
    }

    function closeDeleteModal() {
        state.pendingDeleteTaskId = null;
        deleteModal?.classList.add('is-hidden');
        deleteModal?.setAttribute('aria-hidden', 'true');
    }

    function deletePendingTask() {
        const project = findProject();

        if (!project || state.pendingDeleteTaskId === null) {
            return;
        }

        project.tasks = project.tasks.filter((task) => task.id !== state.pendingDeleteTaskId);
        closeDeleteModal();
        showToast('Tarea eliminada');
        renderProjects();
        renderDetail();
    }

    function updateTaskStatus(taskId, status) {
        const task = findTask(taskId);

        if (!task) {
            return;
        }

        task.status = status;
        showToast('Estado actualizado');
        renderDetail();
    }

    function updateProjectDescriptionCount() {
        projectDescriptionCount.textContent = projectDescriptionInput.value.length;
    }

    workspace.addEventListener('click', (event) => {
        const target = event.target.closest('button');

        if (!target) {
            return;
        }

        if (target.matches('[data-open-project]')) {
            showProjectDetail(target.dataset.openProject);
        } else if (target.matches('[data-edit-project]')) {
            openProjectForm(target.dataset.editProject);
        } else if (target.matches('[data-edit-task]')) {
            state.selectedTaskId = Number(target.dataset.editTask);
            openTaskForm(state.selectedTaskId);
        } else if (target.matches('[data-delete-task]')) {
            openDeleteModal(target.dataset.deleteTask);
        } else if (target.matches('[data-filter]')) {
            state.taskFilter = target.dataset.filter;
            renderDetail();
        } else if (target.dataset.action === 'new-project') {
            openProjectForm();
        } else if (target.dataset.action === 'new-task') {
            openTaskForm();
        } else if (target.dataset.action === 'edit-project') {
            openProjectForm(state.selectedProjectId);
        } else if (target.dataset.action === 'back-projects') {
            renderProjects();
            showView('projects');
        } else if (target.dataset.action === 'back-detail' || target.dataset.action === 'cancel-task-form') {
            renderDetail();
            showView('project-detail');
        } else if (target.dataset.action === 'cancel-project-form') {
            showView(projectIdInput.value ? 'project-detail' : 'projects');
        } else if (target.dataset.action === 'cancel-delete') {
            closeDeleteModal();
        } else if (target.dataset.action === 'confirm-delete') {
            deletePendingTask();
        }
    });

    workspace.addEventListener('change', (event) => {
        if (event.target.matches('[data-status-task]')) {
            updateTaskStatus(event.target.dataset.statusTask, event.target.value);
        }
    });

    projectForm.addEventListener('submit', saveProject);
    taskForm.addEventListener('submit', saveTask);
    projectDescriptionInput.addEventListener('input', updateProjectDescriptionCount);
    projectNameInput.addEventListener('input', () => clearValidation(projectNameInput));
    taskTitleInput.addEventListener('input', () => clearValidation(taskTitleInput));

    renderProjects();
}

function showValidation(input, message) {
    const error = document.querySelector(`[data-error-for="${input.id}"]`);

    input.classList.add('is-invalid');

    if (error) {
        error.textContent = message;
        error.classList.remove('is-hidden');
    }
}

function clearValidation(input) {
    const error = document.querySelector(`[data-error-for="${input.id}"]`);

    input.classList.remove('is-invalid');

    if (error) {
        error.textContent = '';
        error.classList.add('is-hidden');
    }
}

function nextId(items) {
    return Math.max(0, ...items.map((item) => item.id)) + 1;
}

function formatDate(date) {
    if (!date) {
        return 'Sin fecha';
    }

    return dateFormatter.format(new Date(`${date}T00:00:00`));
}

function escapeHtml(value) {
    const element = document.createElement('span');
    element.textContent = value;

    return element.innerHTML;
}

setupPasswordToggles();
setupProjectWorkspace();
