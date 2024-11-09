<?php

namespace App\Http\Controllers;

use App\Exports\TasksExport;
use App\Service\ExportService;
use App\Task;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
class ExportController
{
    /**
     * @var PDF
     */
    private $pdf;
    /**
     * @var ExportService
     */
    private $exportService;
    public function __construct(PDF $pdf, ExportService $exportService)
    {
        $this->pdf = $pdf;
        $this->exportService = $exportService;
    }

    /**
     * @param Request $request
     * @return RedirectResponse|StreamedResponse
     */
    public function exportCsv(Request $request)
    {
        $user = $request->user();

        // Vérifier si l'utilisateur est connecté
        if (!$user) {
            return redirect()->route('login');
        }
        // Récupérer les tâches de l'utilisateur
        $tasks = Task::where('user_id', $user->id)->get();
        // Définir le nom du fichier CSV
        $filename = 'tasks_' . date('Y-m-d') . '.csv';
        return response()->stream(function () use ($tasks) {
           $this->exportService->exportCsv($tasks);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse|BinaryFileResponse
     */
    public function exportExcel(Request $request)
    {
        $user = $request->user();
        // Vérifier si l'utilisateur est connecté
        if (!$user) {
            return redirect()->route('login');
        }
        // Exporter les tâches en tant que fichier Excel
        return Excel::download(new TasksExport($user->id), 'tasks_' . date('Y-m-d') . '.xlsx');
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function exportPdf(Request $request)
    {
        $user = $request->user();

        // Vérifier si l'utilisateur est connecté
        if (!$user) {
            return redirect()->route('login');
        }
        $pdf = $this->exportService->exportPdf($user, $this->pdf);
        // Télécharger le PDF
        return $pdf->download('tasks_' . date('Y-m-d') . '.pdf');
    }
}
