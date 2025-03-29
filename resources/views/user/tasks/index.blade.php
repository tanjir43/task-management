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
@include('user.tasks.partial.formModal')

<!-- Delete Confirmation Modal -->
@include('user.tasks.partial.deleteModal')

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
<script src="{{asset('js/task.js')}}"></script>
@endsection
