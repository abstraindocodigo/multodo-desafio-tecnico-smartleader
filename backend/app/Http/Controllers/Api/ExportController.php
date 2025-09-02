<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ExportService;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    protected $exportService;

    public function __construct(ExportService $exportService)
    {
        $this->middleware('auth:api');
        $this->exportService = $exportService;
    }

    public function exportTasks(Request $request)
    {
        $user = auth()->user();

        // Preparar filtros
        $filters = [];
        if ($request->has('status')) {
            $filters['status'] = $request->status;
        }
        if ($request->has('priority')) {
            $filters['priority'] = $request->priority;
        }
        if ($request->has('user_id')) {
            $filters['user_id'] = $request->user_id;
        }

        try {
            // Usar o ExportService
            $filename = $this->exportService->exportTasksToCsv($user, $filters);

            return response()->json([
                'message' => 'Arquivo gerado com sucesso',
                'download_url' => route('api.export.download', ['filename' => $filename]),
                'filename' => $filename
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao gerar arquivo de exportação',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function downloadExport($filename)
    {
        try {
            $filepath = $this->exportService->getExportFilePath($filename);

            return response()->download($filepath, $filename, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Arquivo não encontrado',
                'message' => $e->getMessage()
            ], 404);
        }
    }
}
