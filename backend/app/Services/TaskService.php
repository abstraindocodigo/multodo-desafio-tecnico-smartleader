<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\TaskCreated;
use App\Mail\TaskCompleted;
use Exception;
use Illuminate\Support\Facades\Log;

class TaskService
{

    public function getTasks(User $user, array $filters = [])
    {
        $query = Task::where('company_id', $user->company_id)
            ->with(['user', 'company']);


        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        $perPage = $filters['per_page'] ?? 10;
        $page = $filters['page'] ?? 1;

        return $query->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function getTask(int $id, User $user): Task
    {
        $task = Task::where('company_id', $user->company_id)
            ->where('id', $id)
            ->with(['user', 'company'])
            ->first();

        if (!$task) {
            throw new Exception('Tarefa não encontrada', 404);
        }

        return $task;
    }

    public function createTask(array $data, User $user): Task
    {
        $task = Task::create([
            'company_id' => $user->company_id,
            'user_id' => $user->id,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? 'pendente',
            'priority' => $data['priority'] ?? 'media',
            'due_date' => $data['due_date'] ?? null,
        ]);


        $this->sendTaskCreatedEmail($user, $task);

        return $task->load(['user', 'company']);
    }


    public function updateTask(int $id, array $data, User $user): Task
    {
        $task = Task::where('company_id', $user->company_id)
            ->where('id', $id)
            ->first();

        if (!$task) {
            throw new Exception('Tarefa não encontrada', 404);
        }

        $oldStatus = $task->status;

        $task->update($data);


        if ($oldStatus !== 'concluida' && $task->status === 'concluida') {
            $this->sendTaskCompletedEmail($user, $task);
        }

        return $task->load(['user', 'company']);
    }


    public function deleteTask(int $id, User $user): bool
    {
        $task = Task::where('company_id', $user->company_id)
            ->where('id', $id)
            ->first();

        if (!$task) {
            throw new Exception('Tarefa não encontrada', 404);
        }

        return $task->delete();
    }


    private function sendTaskCreatedEmail(User $user, Task $task): void
    {
        try {
            Mail::to($user->email)->send(new TaskCreated($task));
        } catch (Exception $e) {
            Log::error('Erro ao enviar email de criação de tarefa: ' . $e->getMessage());
        }
    }


    private function sendTaskCompletedEmail(User $user, Task $task): void
    {
        try {
            Mail::to($user->email)->send(new TaskCompleted($task));
        } catch (Exception $e) {
            Log::error('Erro ao enviar email de conclusão de tarefa: ' . $e->getMessage());
        }
    }
}
