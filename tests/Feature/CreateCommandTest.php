<?php

namespace Tests\Feature;

use App\Enums\TodoState;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CreateCommandTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreate()
    {
        $title = 'foo bar baz';

        $this->artisan("create '{$title}'")
            ->assertExitCode(0)
            ->expectsOutputToContain('Pending Todos')
            ->expectsOutputToContain($title);

        $this->assertDatabaseHas('todos', [
            'title' => $title,
            'state' => TodoState::PENDING,
        ]);
    }

    public function testCreateWithoutTitle()
    {
        $title = 'foo bar baz';

        $this->artisan('create')
            ->assertExitCode(0)
            ->expectsQuestion('What is the title of the todo?', $title)
            ->expectsOutputToContain('Pending Todos')
            ->expectsOutputToContain($title);

        $this->assertDatabaseHas('todos', [
            'title' => $title,
            'state' => TodoState::PENDING,
        ]);
    }
}
