<?php

namespace App\Exports;

use App\Task;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TasksExport implements FromCollection, WithHeadings
{
    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
    * @return Collection
    */
    public function collection(): Collection
    {
        return Task::where('user_id', $this->userId)
            ->get(['title', 'description', 'due_date', 'completed'])
            ->map(function ($task) {
                $task->completed = $task->completed ? 'Oui' : 'Non'; // Transformation de la valeur
                return $task;
            });
    }
    public function headings(): array
    {
        return [
            'Title',
            'Description',
            'Due Date',
            'Completed',
        ];
    }
}
