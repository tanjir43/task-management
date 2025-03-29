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
@include('user.projects.partial.formModal')

<!-- Delete Confirmation Modal -->
@include('user.projects.partial.deleteModal')
@endsection

@section('scripts')
    <script src="{{asset('js/project.js')}}"></script>
@endsection
