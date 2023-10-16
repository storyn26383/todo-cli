<?php

namespace Tests\Feature;

use App\Enums\TodoState;
use App\Models\Todo;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class EditCommandTest extends TestCase
{
    use DatabaseMigrations;

    public function testEdit()
    {
        $todo = Todo::factory()->pending()->create();

        $title = 'foo bar baz';

        $this->artisan("edit {$todo->id} '{$title}'")
            ->assertExitCode(0)
            ->expectsOutputToContain('Pending Todos')
            ->expectsOutputToContain($title);

        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'title' => $title,
            'state' => TodoState::PENDING,
        ]);
    }

    public function testEditWithoutTitle()
    {
        $todo = Todo::factory()->pending()->create();

        $title = 'foo bar baz';

        $this->artisan("edit {$todo->id}")
            ->assertExitCode(0)
            ->expectsQuestion('What is the new title?', $title)
            ->expectsOutputToContain('Pending Todos')
            ->expectsOutputToContain($title);

        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'title' => $title,
            'state' => TodoState::PENDING,
        ]);
    }

    public function testEditWithoutIdAndTitle()
    {
        $todo = Todo::factory()->pending()->create();

        $title = 'foo bar baz';

        $this->artisan('edit')
            ->assertExitCode(0)
            ->expectsChoice('Which todo do you want to edit?', $todo->id, [$todo->id => $todo->title])
            ->expectsQuestion('What is the new title?', $title)
            ->expectsOutputToContain('Pending Todos')
            ->expectsOutputToContain($title);

        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'title' => $title,
            'state' => TodoState::PENDING,
        ]);
    }
}
