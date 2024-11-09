<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Service\TaskService;
use App\Task;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * @var TaskService
     */
    private $taskService;
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }


    /**
     * @param Request $request
     * @return RedirectResponse|Response|View
     */
    public function index(Request $request)
    {

        try {
            $user = $request->user();

            if (!$user) {
                return redirect()->route('login');
            }
            $tasks = $this->taskService->getTasks($user);
            return view('tasks.tasks', compact('tasks'));
        } catch (Exception $e) {
            return response()->view('errors.general', [
                'error' => 'Erreur lors de la récupération des tâches',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse|View
     */
    public function show(Request $request, int $id)
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login');
        }
        [$task, $tasks] = $this->taskService->createTask($user, $id);
        return view('tasks.add', compact('task', 'tasks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreTaskRequest $request
     * @param int $id
     * @return RedirectResponse
     * @throws Exception
     */
    public function store(StoreTaskRequest  $request, int $id): RedirectResponse
    {
        try {
            if (!auth()->check()) {
                return redirect()->route('login')->with('error', 'Vous devez être connecté pour ajouter une tâche.');
            }
            $data = $request->validated();
            $response = $this->taskService->createOrUpdate($id, $data);
            return redirect()->route('tasks.index')->with($response['status'], $response['message']);

        }catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $task = Task::find($id);
        if (!$task) {
            return redirect()->route('tasks.index')->with('error', 'La tâche spécifiée est introuvable.');

        }
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'La tâche a été supprimée avec succès.');
    }

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function markAscompleted(int $id): RedirectResponse
    {
        $task = Task::find($id);
        if (!$task) {
            return redirect()->route('tasks.index')->with('error', 'La tâche spécifiée est introuvable.');
        }
        $task->completed = true;
        $task->save();
        return redirect()->back()->with('success', 'La tâche a été marquée comme terminée.');
    }

    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:1',
        ]);
        $query = $request->input('query');
        $tasks = $this->taskService->search($query);
        // Retourner les résultats à la vue (ou à l'API)
        return view('tasks.tasks', compact('tasks'));
    }
}
