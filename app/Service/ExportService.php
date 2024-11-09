<?php

namespace App\Service;

use App\Task;
use App\User;
use Barryvdh\DomPDF\PDF;

class ExportService
{

    public function __construct()
    {
    }

    /**
     * @param User $user
     * @param PDF $pdf
     * @return PDF
     */
    public function exportPdf(User $user, PDF $pdf): PDF
    {
        // Récupérer les tâches de l'utilisateur
        $tasks = Task::where('user_id', $user->id)->get();

        // Charger la vue pour le PDF
        return $pdf->loadView('pdf.pdf', compact('tasks'));
    }

    public function exportCsv($tasks): void
    {
        $handle = fopen('php://output', 'w');
        // Définir l'en-tête pour le contenu UTF-8
        // Écrire l'en-tête UTF-8 BOM (Byte Order Mark) pour Excel
        fwrite($handle, "\xEF\xBB\xBF");
        // Écrire l'en-tête du CSV
        fputcsv($handle, ['Title', 'Description', 'Due Date', 'Completed']);

        // Écrire les données des tâches dans le CSV
        foreach ($tasks as $task) {
            fputcsv($handle, [
                $task->title,
                $task->description,
                $task->due_date,
                $task->completed ? 'Yes' : 'No'
            ]);
        }
        // Fermer le fichier
        fclose($handle);
    }
}
