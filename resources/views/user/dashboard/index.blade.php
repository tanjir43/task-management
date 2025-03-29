<!-- resources/views/dashboard/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h2 class="mb-4">Dashboard</h2>

            <div class="row">
                <!-- Total Projects Card -->
                <div class="col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Projects</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $projectCount }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-folder fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Tasks Card -->
                <div class="col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Total Tasks</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $taskCount }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-tasks fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Quick Links</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="{{ route('tasks.index') }}" class="btn btn-primary btn-block mb-3">
                                        <i class="fas fa-list mr-2"></i> Manage Tasks
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ route('projects.index') }}" class="btn btn-success btn-block mb-3">
                                        <i class="fas fa-folder-open mr-2"></i> Manage Projects
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    .text-primary {
        color: #4e73df !important;
    }
    .text-success {
        color: #1cc88a !important;
    }
    .text-gray-300 {
        color: #dddfeb !important;
    }
    .text-gray-800 {
        color: #5a5c69 !important;
    }
    .text-xs {
        font-size: 0.7rem;
    }
    .btn-block {
        display: block;
        width: 100%;
    }
</style>
@endsection
