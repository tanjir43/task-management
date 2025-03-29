<?php

namespace App\Http\Controllers\User;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProjectRequest;
use App\Repositories\Interfaces\ProjectRepositoryInterface;

class ProjectController extends Controller
{
    protected $projectRepository;

    public function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function index()
    {
        $projects = $this->projectRepository->all();

        return view('user.projects.index', compact('projects'));
    }

    public function store(ProjectRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->all();
            $data['created_by'] = Auth::id();

            $project = $this->projectRepository->create($data);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Project created successfully!',
                'project' => $project
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Error occurred while creating project: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(ProjectRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $data = $request->all();
            $data['updated_by'] = Auth::id();

            $project = $this->projectRepository->update($id, $data);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Project updated successfully!',
                'project' => $project
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Error occurred while updating project: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $project = $this->projectRepository->find($id);
            $project->update(['deleted_by' => Auth::id()]);

            $this->projectRepository->delete($id);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Project deleted successfully!'
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Error occurred while deleting project: ' . $e->getMessage()
            ], 500);
        }
    }
}
