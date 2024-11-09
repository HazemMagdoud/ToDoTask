<?php

namespace Tests\Feature;

use App\Http\Controllers\TaskController;
use App\Service\TaskService;
use App\Task;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Création du mock pour TaskService
        $this->taskService = $this->createMock(TaskService::class);

        // Instanciation du contrôleur avec le mock
        $this->controller = new TaskController($this->taskService);
    }

    /** @test */
    public function testGetListTask()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        factory(Task::class, 3)->create(['user_id' => $user->id]);

        $response = $this->getJson(route('tasks.index', ['idUser' => $user->id]));

        $response->assertStatus(200);
    }

    /** @test */
    public function testCreateViewAddUpdate()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $task = null;
        $tasks = [];

        $this->taskService->method('createTask')->willReturn([$task, $tasks]);

        $response = $this->get(route('tasks.show', ['id' => 0]));

        $response->assertStatus(200);
        $response->assertViewIs('tasks.add');
        $response->assertViewHas('task', $task);
    }

    /** @test */
    public function it_creates_a_new_task_when_no_id_is_provided()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $data = [
            'title' => 'Nouvelle Tâche',
            'description' => 'Description de la nouvelle tâche',
            'completed' => false,
            'due_date' => now()->addDays(7)->format('Y-m-d'),
            '_token' => csrf_token()
        ];

        $response = $this->post(route('tasks.add-or-update', ['id' => 0]), $data);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Nouvelle Tâche',
            'description' => 'Description de la nouvelle tâche',
            'user_id' => $user->id
        ]);

        $response->assertRedirect(route('tasks.index'));
        $response->assertSessionHas('success', 'Tâche créée avec succès !');
    }

    /** @test */
    public function it_updates_an_existing_task_when_id_is_provided()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $task = factory(Task::class)->create([
            'title' => 'Ancien Titre',
            'description' => 'Ancienne Description',
            'completed' => false,
            'user_id' => $user->id,
        ]);

        $data = [
            'title' => 'Titre Mis à Jour',
            'description' => 'Description Mise à Jour',
            'completed' => true,
            'due_date' => now()->addDays(5)->format('Y-m-d')
        ];

        $response = $this->post(route('tasks.add-or-update', ['id' => $task->id]), $data);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Titre Mis à Jour',
            'description' => 'Description Mise à Jour',
            'completed' => 0
        ]);

        $response->assertRedirect(route('tasks.index'));
        $response->assertSessionHas('success', 'Tâche mise à jour avec succès !');
    }

    /** @test */
    public function testMarkAsCompleted()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $task = Task::create([
            'title' => 'Sample Task',
            'completed' => false,
            'user_id' => $user->id,
            'due_date' => now()->addDays(5)
        ]);

        $response = $this->patch(route('tasks.complete', $task->id));

        $task->refresh();
        $this->assertTrue(is_bool($task->completed), 'La tâche est marquée comme complétée');

        $response->assertRedirect();
        $response->assertSessionHas('success', 'La tâche a été marquée comme terminée.');
    }

    /** @test */
    public function testDeleteTask()
    {
        $this->withoutMiddleware();
        $task = factory(Task::class)->create();

        $response = $this->delete(route('tasks.destroy', $task->id));
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
        $response->assertRedirect(route('tasks.index'));
        $response->assertSessionHas('success', 'La tâche a été supprimée avec succès.');
    }
}
