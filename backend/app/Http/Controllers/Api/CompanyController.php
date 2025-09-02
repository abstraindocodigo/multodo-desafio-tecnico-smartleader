<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Get all users from the authenticated user's company
     */
    public function getCompanyUsers()
    {
        $user = Auth::user();
        $users = User::where('company_id', $user->company_id)
            ->select('id', 'name', 'email', 'is_company_admin', 'created_at')
            ->get();

        return response()->json([
            'data' => $users
        ]);
    }

    /**
     * Get company statistics (admin only)
     */
    public function getCompanyStats()
    {
        $user = Auth::user();

        $totalUsers = User::where('company_id', $user->company_id)->count();
        $totalTasks = Task::whereHas('user', function ($query) use ($user) {
            $query->where('company_id', $user->company_id);
        })->count();

        $completedTasks = Task::whereHas('user', function ($query) use ($user) {
            $query->where('company_id', $user->company_id);
        })->where('status', 'concluida')->count();

        $pendingTasks = Task::whereHas('user', function ($query) use ($user) {
            $query->where('company_id', $user->company_id);
        })->where('status', 'pendente')->count();

        $inProgressTasks = Task::whereHas('user', function ($query) use ($user) {
            $query->where('company_id', $user->company_id);
        })->where('status', 'em_andamento')->count();

        return response()->json([
            'data' => [
                'total_users' => $totalUsers,
                'total_tasks' => $totalTasks,
                'completed_tasks' => $completedTasks,
                'pending_tasks' => $pendingTasks,
                'in_progress_tasks' => $inProgressTasks
            ]
        ]);
    }

    /**
     * Get tasks for a specific user (admin only)
     */
    public function getUserTasks($userId)
    {
        $user = Auth::user();

        // Verify the target user belongs to the same company
        $targetUser = User::where('id', $userId)
            ->where('company_id', $user->company_id)
            ->first();

        if (!$targetUser) {
            return response()->json([
                'error' => 'Usuário não encontrado ou não pertence à sua empresa.'
            ], 404);
        }

        $tasks = Task::where('user_id', $userId)->get();

        return response()->json([
            'data' => $tasks
        ]);
    }

    /**
     * Get all tasks from all users in the company (admin only)
     */
    public function getAllUsersTasks()
    {
        $user = Auth::user();

        $tasks = Task::whereHas('user', function ($query) use ($user) {
            $query->where('company_id', $user->company_id);
        })->with('user:id,name,email')->get();

        return response()->json([
            'data' => $tasks
        ]);
    }
}
