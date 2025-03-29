<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo $__env->make('components.user-meta', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <title><?php echo e(config('app.name', 'Task Manager')); ?></title>

    <?php echo $__env->make('components.user-style', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->yieldContent('styles'); ?>

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
            <a class="navbar-brand" href="<?php echo e(url('/')); ?>">
                <?php echo e(config('app.name', 'Task Manager')); ?>

            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('dashboard')); ?>">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('tasks.index')); ?>">Tasks</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('projects.index')); ?>">Projects</a>
                    </li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    <?php if(auth()->guard()->guest()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('login')); ?>">Login</a>
                        </li>
                        <?php if(Route::has('register')): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo e(route('register')); ?>">Register</a>
                            </li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php echo e(Auth::user()->name); ?>

                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li>
                                    <a class="dropdown-item" href="<?php echo e(route('logout')); ?>"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                </li>
                                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                                    <?php echo csrf_field(); ?>
                                </form>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <?php echo $__env->yieldContent('content'); ?>
    </main>


    <?php echo $__env->make('components.user-script', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php $__env->startSection('routes'); ?>
        <script>
            routes = {
                'projects.store'  : '<?php echo e(route('projects.store')); ?>',
                'projects.update' : '<?php echo e(route('projects.update', ['id' => ':id'])); ?>'.replace(':id', '{id}'),
                'projects.destroy': '<?php echo e(route('projects.destroy', ['id' => ':id'])); ?>'.replace(':id', '{id}'),
                'tasks.index'     : '<?php echo e(route('tasks.index')); ?>',
                'tasks.store'     : '<?php echo e(route('tasks.store')); ?>',
                'tasks.update'    : '<?php echo e(route('tasks.update', ['id' => ':id'])); ?>'.replace(':id', '{id}'),
                'tasks.destroy'   : '<?php echo e(route('tasks.destroy', ['id' => ':id'])); ?>'.replace(':id', '{id}'),
                'tasks.reorder'   : '<?php echo e(route('tasks.reorder')); ?>'
            };
        </script>
    <?php echo $__env->yieldSection(); ?>

<?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH /home/tanjir/WorkStation/sciolo/task-management/resources/views/layouts/app.blade.php ENDPATH**/ ?>