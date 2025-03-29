@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Project Management</span>
                    <div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#projectModal">
                            Add New Project
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="50">ID</th>
                                    <th>Project Name</th>
                                    <th>Tasks Count</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($projects as $project)
                                <tr>
                                    <td>{{ $project->id }}</td>
                                    <td>{{ $project->name }}</td>
                                    <td>{{ $project->tasks->count() }}</td>
                                    <td>{{ $project->created_at->format('M j, Y \a\t g:i A') }}</td>
                                    <td>
                                        <a href="{{ route('tasks.index', ['project_id' => $project->id]) }}" class="btn btn-sm btn-info">
                                            View Tasks
                                        </a>
                                        <button class="btn btn-sm btn-primary edit-project"
                                                data-id="{{ $project->id }}"
                                                data-name="{{ $project->name }}">
                                            Edit
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-project" data-id="{{ $project->id }}">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No projects found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Project Modal -->
<div class="modal fade" id="projectModal" tabindex="-1" aria-labelledby="projectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="projectModalLabel">Add New Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="projectForm">
                    <input type="hidden" id="project-id">
                    <div class="mb-3">
                        <label for="project-name" class="form-label">Project Name</label>
                        <input type="text" class="form-control" id="project-name" required>
                        <div class="invalid-feedback" id="name-error"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveProjectBtn">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this project? All associated tasks will be affected.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Project modal
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
                ? "{{ url('projects') }}/" + id
                : "{{ route('projects.store') }}";

            const method = isEditing ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                type: method,
                data: {
                    name: name
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
                    url: "{{ url('projects') }}/" + projectToDelete,
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
</script>
@endsection
