<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Task;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(int $idUser)
    {
        try {
            $userId = auth()->user()->id;
            $tasks = Task::where('user_id', $userId)->get();
            return response()->json($tasks, 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la récupération des utilisateurs',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // Validation des données
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'required|boolean',
            'due_date' => 'nullable|date',
        ]);
        $task = Task::create($validatedData);
        return response()->json($task, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Tâche non trouvée'], 404);
        }

        return response()->json($task);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        // Validation des données
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'boolean',
            'due_date' => 'nullable|date',
        ]);

        // Récupérer la tâche par son ID
        $task = Task::find($id);

        // Vérifier si la tâche existe
        if (!$task) {
            return response()->json(['message' => 'Tâche non trouvée'], 404);
        }

        // Mettre à jour les informations de la tâche
        $task->update($request->all());

        // Retourner la tâche mise à jour
        return response()->json($task);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        // Vérifier si la tâche existe
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'error' => 'La tâche spécifiée est introuvable.'
            ], 404);
        }
        $task->delete();
        return response()->json([
            'message' => 'La tâche a été supprimée avec succès.'
        ], 200);
    }
}
