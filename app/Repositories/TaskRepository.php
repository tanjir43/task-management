<?php

namespace App\Repositories;

use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Support\Facades\DB;

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

    public function getTrashed()
    {
        return $this->model->onlyTrashed()->get();
    }

    public function withTrashedItems()
    {
        return $this->model->withTrashed()->get();
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

    public function restore($id)
    {
        if (strpos($id, ',') !== false) {
            $ids = explode(',', $id);
            return $this->model->withTrashed()->whereIn('id', $ids)->restore();
        }

        $task = $this->model->withTrashed()->findOrFail($id);
        $task->restore();
        return $task;
    }

    public function forceDelete($id)
    {
        if (strpos($id, ',') !== false) {
            $ids = explode(',', $id);
            return $this->model->withTrashed()->whereIn('id', $ids)->forceDelete();
        }

        $task = $this->model->withTrashed()->findOrFail($id);
        $task->forceDelete();
        return $task;
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

namespace App\Repositories;

use App\Models\Project;
use App\Repositories\Interfaces\ProjectRepositoryInterface;

class ProjectRepository implements ProjectRepositoryInterface
{
    protected $model;

    public function __construct(Project $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function getTrashed()
    {
        return $this->model->onlyTrashed()->get();
    }

    public function withTrashedItems()
    {
        return $this->model->withTrashed()->get();
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
        $project = $this->model->findOrFail($id);
        $project->update($data);
        return $project;
    }

    public function delete($id): bool
    {
        $ids = explode(',', $id);

        $projects = $this->model->whereIn('id', $ids)->get();

        if ($projects->isEmpty()) {
            return false;
        }

        return $this->model->whereIn('id', $ids)->delete();
    }

    public function restore($id)
    {
        if (strpos($id, ',') !== false) {
            $ids = explode(',', $id);
            return $this->model->withTrashed()->whereIn('id', $ids)->restore();
        }

        $project = $this->model->withTrashed()->findOrFail($id);
        $project->restore();
        return $project;
    }

    public function forceDelete($id)
    {
        if (strpos($id, ',') !== false) {
            $ids = explode(',', $id);
            return $this->model->withTrashed()->whereIn('id', $ids)->forceDelete();
        }

        $project = $this->model->withTrashed()->findOrFail($id);
        $project->forceDelete();
        return $project;
    }
}
