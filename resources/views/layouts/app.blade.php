<!DOCTYPE html>
<html lang="en">

<head>
    @include('components.user-meta')
    <title>{{ config('app.name', 'Task Manager') }}</title>

    @include('components.user-style')
    @yield('styles')

    <script>
        var routes = {};

        function route(name, params = {}) {
            if (!routes[name]) {
                throw new Error(`Route ${name} not found`);
            }

            let url = routes[name];

            for (let param in params) {
                url = url.replace(`{${param}}`, params[param]);
            }

            return url;
        }
    </script>
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Task Manager') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tasks.index') }}">Tasks</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('projects.index') }}">Projects</a>
                    </li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">Register</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                </li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>


    @include('components.user-script')

    @section('routes')
        <script>
            routes = {
                'projects.store'  : '{{ route('projects.store') }}',
                'projects.update' : '{{ route('projects.update', ['id' => ':id']) }}'.replace(':id', '{id}'),
                'projects.destroy': '{{ route('projects.destroy', ['id' => ':id']) }}'.replace(':id', '{id}'),
                'tasks.index'     : '{{ route('tasks.index') }}',
                'tasks.store'     : '{{ route('tasks.store') }}',
                'tasks.update'    : '{{ route('tasks.update', ['id' => ':id']) }}'.replace(':id', '{id}'),
                'tasks.destroy'   : '{{ route('tasks.destroy', ['id' => ':id']) }}'.replace(':id', '{id}'),
                'tasks.reorder'   : '{{ route('tasks.reorder') }}'
            };
        </script>
    @show

@yield('scripts')
</body>
</html>
