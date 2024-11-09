<?php

namespace App\Service;

use App\Task;
use App\User;
use Carbon\Carbon;

class TaskService
{
    public const ARRAY_OF_COLOR =  [
        '#33FFF9',
        '#FFC733',
        '#FFEB99',
        '#CDEFF8',
        '#FFE4E1',
        '#FFD8A9',
        'f1f7bc'
    ];
    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getTasks(User $user)
    {
        $tasks = Task::where('user_id', $user->id)->get();
        foreach ($tasks as $task) {
            $task->color = self::ARRAY_OF_COLOR[array_rand(self::ARRAY_OF_COLOR)];
        }

        return $tasks??[];
    }

    /**
     * @param User $user
     * @param int $id
     * @return array
     */
    public function createTask(User $user, int $id): array
    {
        $task = null;
        if (!empty($id)) {
            $task = Task::findOrFail($id);
            $task->due_date = Carbon::parse($task->due_date);
        }
        $tasks = $this->getTasks($user);
        return [$task, $tasks];
    }

    /**
     * @param int $id
     * @param array $data
     * @return string[]
     */
    public function createOrUpdate(int $id, array $data): array
    {
        $data['completed'] = $data['completed'] ?? false;
        if ($id) {
            // Mise à jour de la tâche existante
            $task = Task::findOrFail($id);
            $task->update($data);
            return ['status' => 'success', 'message' => 'Tâche mise à jour avec succès !'];
        }
        // Création d'une nouvelle tâche
        Task::create(array_merge($data, ['user_id' => auth()->id()]));
        return ['status' => 'success', 'message' => 'Tâche créée avec succès !'];

    }
    public function search(string $query)
    {
        // Recherche dans la table tasks (ajustez les champs selon vos besoins)
        return Task::where('title', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->get();
    }
}
