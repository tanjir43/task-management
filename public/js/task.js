$(document).ready(function() {
    // Sortable
    const taskList = document.getElementById('task-list');
    if (taskList) {
        new Sortable(taskList, {
            animation: 150,
            ghostClass: 'bg-light',
            onEnd: function(evt) {
                updatePriorities();
            }
        });
    }

    function updatePriorities() {
        const taskIds = [];
        const priorities = {};

        $('.task-item').each(function(index) {
            const id = $(this).data('id');
            const newPriority = index + 1;

            taskIds.push(id);
            priorities[id] = newPriority;

            $(this).find('td:eq(3)').text(newPriority);
        });

        $.ajax({
            url: route('tasks.reorder'),
            type: "POST",
            data: {
                priorities: priorities
            },
            success: function(response) {
                if (response.status === 'success') {

                }
            }
        });
    }

    $('#project-filter').change(function() {
        const projectId = $(this).val();
        const baseUrl = route('tasks.index');
        window.location.href = baseUrl + (projectId ? "?project_id=" + projectId : "");
    });

    // Task modal
    let isEditing = false;

    $('.edit-task').click(function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const projectId = $(this).data('project');

        $('#task-id').val(id);
        $('#task-name').val(name);
        $('#task-project').val(projectId);

        $('#taskModalLabel').text('Edit Task');
        isEditing = true;
        $('#taskModal').modal('show');
    });

    $('#taskModal').on('hidden.bs.modal', function() {
        resetTaskForm();
    });

    function resetTaskForm() {
        $('#taskForm').trigger('reset');
        $('#task-id').val('');
        $('#taskModalLabel').text('Add New Task');
        isEditing = false;
        clearErrors();
    }

    function clearErrors() {
        $('#task-name').removeClass('is-invalid');
        $('#task-project').removeClass('is-invalid');
        $('#name-error').text('');
        $('#project-error').text('');
    }

    // Save task
    $('#saveTaskBtn').click(function() {
        clearErrors();

        const id = $('#task-id').val();
        const name = $('#task-name').val();
        const projectId = $('#task-project').val();

        const url = isEditing
            ? route('tasks.update', {id: id})
            : route('tasks.store');

        const method = isEditing ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: method,
            data: {
                name: name,
                project_id: projectId === '' ? null : projectId
            },
            success: function(response) {
                if (response.status === 'success') {
                    $('#taskModal').modal('hide');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;

                    if (errors.name) {
                        $('#task-name').addClass('is-invalid');
                        $('#name-error').text(errors.name[0]);
                    }

                    if (errors.project_id) {
                        $('#task-project').addClass('is-invalid');
                        $('#project-error').text(errors.project_id[0]);
                    }
                }
            }
        });
    });

    // Delete task
    let taskToDelete = null;

    $('.delete-task').click(function() {
        taskToDelete = $(this).data('id');
        $('#deleteModal').modal('show');
    });

    $('#confirmDeleteBtn').click(function() {
        if (taskToDelete) {
            $.ajax({
                url: route('tasks.destroy', {id: taskToDelete}),
                type: "DELETE",
                success: function(response) {
                    if (response.status === 'success') {
                        $('#deleteModal').modal('hide');
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                }
            });
        }
    });
});
