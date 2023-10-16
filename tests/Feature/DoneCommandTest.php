<?php

namespace Tests\Feature;

use App\Enums\TodoState;
use App\Models\Todo;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class DoneCommandTest extends TestCase
{
    use DatabaseMigrations;

    public function testDone()
    {
        $todo = Todo::factory()->pending()->create();
        $todoToDone = Todo::factory()->pending()->create();

        $this->artisan("done {$todoToDone->id}")
            ->assertExitCode(0)
            ->expectsOutputToContain('Pending Todos')
            ->expectsOutputToContain($todo->title)
            ->expectsOutputToContain('Done Todos')
            ->expectsOutputToContain($todoToDone->title);

        $this->assertDatabaseHas('todos', ['id' => $todo->id, 'state' => TodoState::PENDING]);
        $this->assertDatabaseHas('todos', ['id' => $todoToDone->id, 'state' => TodoState::DONE]);
    }

    public function testDoneWithoutId()
    {
        $todo = Todo::factory()->pending()->create();
        $todoToDone = Todo::factory()->pending()->create();

        $answers = [
            $todo->id => $todo->title,
            $todoToDone->id => $todoToDone->title,
        ];

        $this->artisan('done')
            ->assertExitCode(0)
            ->expectsChoice('Which todo do you want to mark as done?', $todoToDone->id, $answers)
            ->expectsOutputToContain('Pending Todos')
            ->expectsOutputToContain($todo->title)
            ->expectsOutputToContain('Done Todos')
            ->expectsOutputToContain($todoToDone->title);

        $this->assertDatabaseHas('todos', ['id' => $todo->id, 'state' => TodoState::PENDING]);
        $this->assertDatabaseHas('todos', ['id' => $todoToDone->id, 'state' => TodoState::DONE]);
    }
}
