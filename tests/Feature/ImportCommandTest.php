<?php

namespace Tests\Feature;

use App\Models\Todo;
use DateTimeInterface;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ImportCommandTest extends TestCase
{
    use DatabaseMigrations;

    public function testImport()
    {
        Todo::factory()->times(10)->create();

        $path = '/test/test.json';
        $todos = Todo::all();

        Todo::truncate();

        File::shouldReceive('exists')->once()->with($path)->andReturn(true);
        File::shouldReceive('get')->once()->with($path)->andReturn($todos->toJson());

        $this->artisan("import {$path}")
            ->assertExitCode(0)
            ->expectsConfirmation('Are you sure you want to import these todos?', 'yes');

        $todos->each(function ($todo) {
            $this->assertDatabaseHas('todos', $this->todoToDatabaseArray($todo));
        });
    }

    public function testCancel()
    {
        Todo::factory()->times(10)->create();

        $path = '/test/test.json';
        $todos = Todo::all();

        Todo::truncate();

        File::shouldReceive('exists')->once()->with($path)->andReturn(true);
        File::shouldReceive('get')->once()->with($path)->andReturn($todos->toJson());

        $this->artisan("import {$path}")
            ->assertExitCode(1)
            ->expectsConfirmation('Are you sure you want to import these todos?', 'no');

        $this->assertDatabaseEmpty('todos');
    }

    public function testInvalidJson()
    {
        $path = '/test/test.json';

        File::shouldReceive('exists')->once()->with($path)->andReturn(true);
        File::shouldReceive('get')->once()->with($path)->andReturn('invalid json');

        $this->artisan("import {$path}")
            ->assertExitCode(1)
            ->expectsOutputToContain('Invalid JSON provided.');
    }

    public function testFileNotExists()
    {
        $path = '/test/test.json';

        File::shouldReceive('exists')->once()->with($path)->andReturn(false);

        $this->artisan("import {$path}")
            ->assertExitCode(1)
            ->expectsOutputToContain("File `{$path}` does not exist.");
    }

    public function testDoNotOverwriteExistsTodos()
    {
        Todo::factory()->times(10)->create();

        $path = '/test/test.json';
        $todos = Todo::all();

        File::shouldReceive('exists')->once()->with($path)->andReturn(true);
        File::shouldReceive('get')->once()->with($path)->andReturn($todos->toJson());

        $this->artisan("import {$path}")
            ->assertExitCode(0)
            ->expectsConfirmation('Are you sure you want to import these todos?', 'yes');

        $todos->each(function ($todo) {
            $this->assertDatabaseHas('todos', $this->todoToDatabaseArray($todo));
            $this->assertDatabaseHas('todos', ['id' => $todo->id + 10] + $this->todoToDatabaseArray($todo));
        });
    }

    private function todoToDatabaseArray(Todo $todo)
    {
        return array_map(function ($attribute) {
            if ($attribute instanceof DateTimeInterface) {
                return $attribute->format('Y-m-d H:i:s');
            }

            return $attribute;
        }, $todo->getAttributes());
    }
}
