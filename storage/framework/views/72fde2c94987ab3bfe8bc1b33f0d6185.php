<?php $__env->startSection('content'); ?>
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
                    <?php if(count($projects) > 0): ?>
                    <div class="mb-3">
                        <label for="project-filter" class="form-label">Filter by Project:</label>
                        <select id="project-filter" class="form-select">
                            <option value="">All Projects</option>
                            <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($project->id); ?>" <?php echo e($projectId == $project->id ? 'selected' : ''); ?>>
                                    <?php echo e($project->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <?php endif; ?>

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
                                <?php $__empty_1 = true; $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr data-id="<?php echo e($task->id); ?>" class="task-item">
                                    <td><?php echo e($task->id); ?></td>
                                    <td><?php echo e($task->name); ?></td>
                                    <td><?php echo e($task->project->name ?? 'None'); ?></td>
                                    <td><?php echo e($task->priority); ?></td>
                                    <td><?php echo e($task->created_at->format('M j, Y \a\t g:i A')); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary edit-task"
                                                data-id="<?php echo e($task->id); ?>"
                                                data-name="<?php echo e($task->name); ?>"
                                                data-project="<?php echo e($task->project_id); ?>">
                                            Edit
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-task" data-id="<?php echo e($task->id); ?>">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center">No tasks found</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Task Modal -->
<?php echo $__env->make('user.tasks.partial.formModal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<!-- Delete Confirmation Modal -->
<?php echo $__env->make('user.tasks.partial.deleteModal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
<script src="<?php echo e(asset('js/task.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/tanjir/WorkStation/sciolo/task-management/resources/views/user/tasks/index.blade.php ENDPATH**/ ?>