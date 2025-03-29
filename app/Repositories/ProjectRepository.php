<?php

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
}
