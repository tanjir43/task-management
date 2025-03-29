$(document).ready(function() {
    let isEditing = false;

    $('.edit-project').click(function() {
        const id = $(this).data('id');
        const name = $(this).data('name');

        $('#project-id').val(id);
        $('#project-name').val(name);

        $('#projectModalLabel').text('Edit Project');
        isEditing = true;
        $('#projectModal').modal('show');
    });

    $('#projectModal').on('hidden.bs.modal', function() {
        resetProjectForm();
    });

    function resetProjectForm() {
        $('#projectForm').trigger('reset');
        $('#project-id').val('');
        $('#projectModalLabel').text('Add New Project');
        isEditing = false;
        clearErrors();
    }

    function clearErrors() {
        $('#project-name').removeClass('is-invalid');
        $('#name-error').text('');
    }

    // Save project
    $('#saveProjectBtn').click(function() {
        clearErrors();

        const id = $('#project-id').val();
        const name = $('#project-name').val();

        const url = isEditing
            ? route('projects.update', {id: id})
            : route('projects.store');

        const method = isEditing ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: method,
            data: {
                name: name,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status === 'success') {
                    $('#projectModal').modal('hide');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;

                    if (errors.name) {
                        $('#project-name').addClass('is-invalid');
                        $('#name-error').text(errors.name[0]);
                    }
                }
            }
        });
    });

    // Delete project
    let projectToDelete = null;

    $('.delete-project').click(function() {
        projectToDelete = $(this).data('id');
        $('#deleteModal').modal('show');
    });

    $('#confirmDeleteBtn').click(function() {
        if (projectToDelete) {
            $.ajax({
                url: route('projects.destroy', {id: projectToDelete}),
                type: "DELETE",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
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
