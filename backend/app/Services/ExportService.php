<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Exception;

class ExportService
{


    public function exportTasksToCsv(User $user, array $filters = []): string
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

        $tasks = $query->orderBy('created_at', 'desc')->get();

        $filename = 'tasks_export_' . date('Y-m-d_H-i-s') . '.csv';
        $filepath = 'exports/' . $filename;

        $csvData = $this->generateCsvData($tasks);

        Storage::disk('local')->put($filepath, $csvData);

        return $filename;
    }


    private function generateCsvData($tasks): string
    {
        // Adicionar BOM para UTF-8
        $csvData = "\xEF\xBB\xBF";

        $headers = [
            'ID',
            'Título',
            'Descrição',
            'Status',
            'Prioridade',
            'Data de Vencimento',
            'Usuário',
            'Empresa',
            'Data de Criação',
            'Data de Atualização'
        ];

        $csvData .= implode(',', $headers) . "\n";

        foreach ($tasks as $task) {
            $row = [
                $task->id,
                '"' . str_replace('"', '""', $task->title ?? '') . '"',
                '"' . str_replace('"', '""', $task->description ?? '') . '"',
                '"' . str_replace('"', '""', $task->status ?? '') . '"',
                '"' . str_replace('"', '""', $task->priority ?? '') . '"',
                '"' . ($task->due_date ? $task->due_date->format('d/m/Y') : '') . '"',
                '"' . str_replace('"', '""', $task->user->name ?? '') . '"',
                '"' . str_replace('"', '""', $task->company->name ?? '') . '"',
                '"' . $task->created_at->format('d/m/Y H:i:s') . '"',
                '"' . $task->updated_at->format('d/m/Y H:i:s') . '"'
            ];

            $csvData .= implode(',', $row) . "\n";
        }

        return $csvData;
    }


    public function getExportFilePath(string $filename): string
    {
        $filepath = 'exports/' . $filename;

        if (!Storage::disk('local')->exists($filepath)) {
            throw new Exception('Arquivo de exportação não encontrado', 404);
        }

        return storage_path('app/' . $filepath);
    }


    public function deleteExportFile(string $filename): bool
    {
        $filepath = 'exports/' . $filename;

        if (Storage::disk('local')->exists($filepath)) {
            return Storage::disk('local')->delete($filepath);
        }

        return false;
    }
}
