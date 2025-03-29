<?php

namespace App\Repositories\Interfaces;

interface ProjectRepositoryInterface
{
    public function all();
    public function getTrashed();
    public function withTrashedItems();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function restore($id);
    public function forceDelete($id);
}
