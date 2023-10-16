<?php

namespace Tests\Feature;

use App\Enums\TodoState;
use App\Models\Todo;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PendingCommandTest extends TestCase
{
    use DatabaseMigrations;

    public function testPeng()
    {
        $todo = Todo::factory()->done()->create();
        $todoToPend = Todo::factory()->done()->create();

        $this->artisan("pend {$todoToPend->id}")
            ->assertExitCode(0)
            ->expectsOutputToContain('Pending Todos')
            ->expectsOutputToContain($todoToPend->title)
            ->expectsOutputToContain('Done Todos')
            ->expectsOutputToContain($todo->title);

        $this->assertDatabaseHas('todos', ['id' => $todo->id, 'state' => TodoState::DONE]);
        $this->assertDatabaseHas('todos', ['id' => $todoToPend->id, 'state' => TodoState::PENDING]);
    }

    public function testPengWithoutId()
    {
        $todo = Todo::factory()->done()->create();
        $todoToPend = Todo::factory()->done()->create();

        $answers = [
            $todo->id => $todo->title,
            $todoToPend->id => $todoToPend->title,
        ];

        $this->artisan('pend')
            ->assertExitCode(0)
            ->expectsChoice('Which todo do you want to mark as pending?', $todoToPend->id, $answers)
            ->expectsOutputToContain('Pending Todos')
            ->expectsOutputToContain($todoToPend->title)
            ->expectsOutputToContain('Done Todos')
            ->expectsOutputToContain($todo->title);

        $this->assertDatabaseHas('todos', ['id' => $todo->id, 'state' => TodoState::DONE]);
        $this->assertDatabaseHas('todos', ['id' => $todoToPend->id, 'state' => TodoState::PENDING]);
    }
}
