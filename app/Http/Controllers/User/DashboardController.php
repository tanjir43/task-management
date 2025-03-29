<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Repositories\Interfaces\ProjectRepositoryInterface;

class DashboardController extends Controller
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

    public function index()
    {
        $taskCount = $this->taskRepository->all()->count();
        $projectCount = $this->projectRepository->all()->count();

        return view('user.dashboard.index', compact('taskCount', 'projectCount'));
    }
}
