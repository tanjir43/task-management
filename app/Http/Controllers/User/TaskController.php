<?php

namespace App\Http\Controllers\User;

use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Repositories\Interfaces\ProjectRepositoryInterface;

class TaskController extends Controller
{
    protected $taskRepository;
    protected $projectRepository;

    public function __construct(
        TaskRepositoryInterface $taskRepository,
        ProjectRepositoryInterface $projectRepository
    ) {
        $this->taskRepository = $taskRepository;
        $this->projectRepository = $projectRepository;
    }

    public function index(Request $request)
    {
        $tasks = $this->taskRepository->all();
        $projects = $this->projectRepository->all();
        $projectId = $request->query('project_id');

        if ($projectId) {
            $tasks = $this->taskRepository->getByProject($projectId);
        }

        if ($request->ajax()) {
            return response()->json([
                'tasks' => $tasks
            ]);
        }

        return view('user.tasks.index', compact('tasks', 'projects', 'projectId'));
    }

    public function store(TaskRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->all();

            if (!isset($data['priority']) || $data['priority'] == null) {
                $data['priority'] = $this->taskRepository->getMaxPriority() + 1;
            }

            $data['created_by'] = Auth::id();

            $task = $this->taskRepository->create($data);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Task created successfully!',
                'task' => $task
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(TaskRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $data = $request->all();
            $data['updated_by'] = Auth::id();

            $task = $this->taskRepository->update($id, $data);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Task updated successfully!',
                'task' => $task
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $task = $this->taskRepository->find($id);
            $task->update(['deleted_by' => Auth::id()]);

            $this->taskRepository->delete($id);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Task deleted successfully!'
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function reorder(Request $request)
    {
        try {
            $priorities = $request->priorities;

            $result = $this->taskRepository->reorder($priorities);

            if ($result) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Tasks reordered successfully!'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error reordering tasks'
                ], 500);
            }

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
