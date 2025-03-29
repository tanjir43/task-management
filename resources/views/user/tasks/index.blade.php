@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Task Management</span>
                    <div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#taskModal">
                            Add New Task
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    @if(count($projects) > 0)
                    <div class="mb-3">
                        <label for="project-filter" class="form-label">Filter by Project:</label>
                        <select id="project-filter" class="form-select">
                            <option value="">All Projects</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ $projectId == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered task-table">
                            <thead>
                                <tr>
                                    <th width="50">ID</th>
                                    <th>Task Name</th>
                                    <th>Project</th>
                                    <th>Priority</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="task-list">
                                @forelse($tasks as $task)
                                <tr data-id="{{ $task->id }}" class="task-item">
                                    <td>{{ $task->id }}</td>
                                    <td>{{ $task->name }}</td>
                                    <td>{{ $task->project->name ?? 'None' }}</td>
                                    <td>{{ $task->priority }}</td>
                                    <td>{{ $task->created_at->format('M j, Y \a\t g:i A') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary edit-task"
                                                data-id="{{ $task->id }}"
                                                data-name="{{ $task->name }}"
                                                data-project="{{ $task->project_id }}">
                                            Edit
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-task" data-id="{{ $task->id }}">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No tasks found</td>
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

<!-- Task Modal -->
<div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskModalLabel">Add New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="taskForm">
                    <input type="hidden" id="task-id">
                    <div class="mb-3">
                        <label for="task-name" class="form-label">Task Name</label>
                        <input type="text" class="form-control" id="task-name" required>
                        <div class="invalid-feedback" id="name-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="task-project" class="form-label">Project</label>
                        <select class="form-select" id="task-project">
                            <option value="">None</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="project-error"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveTaskBtn">Save</button>
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
                Are you sure you want to delete this task?
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
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Sortable.js
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

        // Update priorities after drag and drop
        function updatePriorities() {
            const taskIds = [];
            const priorities = {};

            $('.task-item').each(function(index) {
                const id = $(this).data('id');
                taskIds.push(id);
                priorities[id] = index + 1;
            });

            // Send AJAX request to update priorities
            $.ajax({
                url: "{{ route('tasks.reorder') }}",
                type: "POST",
                data: {
                    priorities: priorities
                }
            });
        }

        // Project filter change
        $('#project-filter').change(function() {
            const projectId = $(this).val();
            window.location.href = "{{ route('tasks.index') }}" + (projectId ? "?project_id=" + projectId : "");
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
                ? "{{ url('tasks') }}/" + id
                : "{{ route('tasks.store') }}";

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
                    url: "{{ url('tasks') }}/" + taskToDelete,
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
