<?php

namespace Tests\Feature;

use App\Enums\TodoState;
use App\Models\Todo;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ArchiveCommandTest extends TestCase
{
    use DatabaseMigrations;

    public function testArchive()
    {
        $todo = Todo::factory()->pending()->create();
        $todoToArchive = Todo::factory()->pending()->create();

        $this->artisan("archive {$todoToArchive->id}")
            ->assertExitCode(0)
            ->expectsOutputToContain('Pending Todos')
            ->expectsOutputToContain($todo->title)
            ->expectsOutputToContain('Archived Todos')
            ->expectsOutputToContain($todoToArchive->title);

        $this->assertDatabaseHas('todos', ['id' => $todo->id, 'state' => TodoState::PENDING]);
        $this->assertDatabaseHas('todos', ['id' => $todoToArchive->id, 'state' => TodoState::ARCHIVED]);
    }

    public function testArchiveWithoutId()
    {
        $todo = Todo::factory()->pending()->create();
        $todoToArchive = Todo::factory()->pending()->create();

        $answers = [
            $todo->id => $todo->title,
            $todoToArchive->id => $todoToArchive->title,
        ];

        $this->artisan('archive')
            ->assertExitCode(0)
            ->expectsChoice('Which todo do you want to mark as archived?', $todoToArchive->id, $answers)
            ->expectsOutputToContain('Pending Todos')
            ->expectsOutputToContain($todo->title)
            ->expectsOutputToContain('Archived Todos')
            ->expectsOutputToContain($todoToArchive->title);

        $this->assertDatabaseHas('todos', ['id' => $todo->id, 'state' => TodoState::PENDING]);
        $this->assertDatabaseHas('todos', ['id' => $todoToArchive->id, 'state' => TodoState::ARCHIVED]);
    }
}
