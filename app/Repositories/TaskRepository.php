<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    protected $model;

    public function __construct(Task $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->orderBy('priority', 'asc')->get();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $task = $this->model->findOrFail($id);
        $task->update($data);
        return $task;
    }

    public function delete($id): bool
    {
        $ids = explode(',', $id);

        $tasks = $this->model->whereIn('id', $ids)->get();

        if ($tasks->isEmpty()) {
            return false;
        }

        return $this->model->whereIn('id', $ids)->delete();
    }

    public function reorder(array $priorities)
    {
        DB::beginTransaction();

        try {
            foreach ($priorities as $id => $priority) {
                $this->model->where('id', $id)->update(['priority' => $priority]);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function getByProject($projectId)
    {
        return $this->model->where('project_id', $projectId)
                          ->orderBy('priority', 'asc')
                          ->get();
    }

    public function getMaxPriority()
    {
        return $this->model->max('priority') ?? 0;
    }
}
