<?php

namespace Tests\Feature;

use App\Models\Todo;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class RemoveCommandTest extends TestCase
{
    use DatabaseMigrations;

    public function testRemove()
    {
        $todo = Todo::factory()->pending()->create();
        $todoToRemove = Todo::factory()->pending()->create();

        $this->artisan("rm {$todoToRemove->id}")
            ->assertExitCode(0)
            ->expectsConfirmation("Todo `[{$todoToRemove->id}] {$todoToRemove->title}` will be removed. Do you wish to continue?", 'yes')
            ->expectsOutputToContain('Pending Todos')
            ->expectsOutputToContain($todo->title)
            ->doesntExpectOutputToContain($todoToRemove->title);

        $this->assertDatabaseHas('todos', ['id' => $todo->id]);
        $this->assertDatabaseMissing('todos', ['id' => $todoToRemove->id]);
    }

    public function testRemoveWithoutId()
    {
        $todo = Todo::factory()->pending()->create();
        $todoToRemove = Todo::factory()->pending()->create();

        $answers = [
            $todo->id => $todo->title,
            $todoToRemove->id => $todoToRemove->title,
        ];

        $this->artisan('rm')
            ->assertExitCode(0)
            ->expectsChoice('Which todo do you want to remove?', $todoToRemove->id, $answers)
            ->expectsConfirmation("Todo `[{$todoToRemove->id}] {$todoToRemove->title}` will be removed. Do you wish to continue?", 'yes')
            ->expectsOutputToContain('Pending Todos')
            ->expectsOutputToContain($todo->title)
            ->doesntExpectOutputToContain($todoToRemove->title);

        $this->assertDatabaseHas('todos', ['id' => $todo->id]);
        $this->assertDatabaseMissing('todos', ['id' => $todoToRemove->id]);
    }
}
