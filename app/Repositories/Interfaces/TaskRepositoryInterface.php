<?php

namespace App\Repositories\Interfaces;

interface TaskRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function reorder(array $priorities);
    public function getByProject($projectId);
    public function getMaxPriority();
}
