<?php

namespace Tests\Feature;

use App\Models\Todo;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ListCommandTest extends TestCase
{
    use DatabaseMigrations;

    public function testList()
    {
        $pending = Todo::factory()->pending()->create();
        $done = Todo::factory()->done()->create();
        $archive = Todo::factory()->archive()->create();

        $this->artisan('ls')
            ->assertExitCode(0)
            ->expectsOutputToContain('Pending Todos')
            ->expectsOutputToContain("[{$pending->id}] {$pending->title}")
            ->doesntExpectOutputToContain('Done Todos')
            ->doesntExpectOutputToContain("[{$done->id}] {$done->title}")
            ->doesntExpectOutputToContain('Archived Todos')
            ->doesntExpectOutputToContain("[{$archive->id}] {$archive->title}");
    }

    public function testListDone()
    {
        $pending = Todo::factory()->pending()->create();
        $done = Todo::factory()->done()->create();
        $archive = Todo::factory()->archive()->create();

        $this->artisan('ls --done')
            ->assertExitCode(0)
            ->doesntExpectOutputToContain('Pending Todos')
            ->doesntExpectOutputToContain("[{$pending->id}] {$pending->title}")
            ->expectsOutputToContain('Done Todos')
            ->expectsOutputToContain("[{$done->id}] {$done->title}")
            ->doesntExpectOutputToContain('Archived Todos')
            ->doesntExpectOutputToContain("[{$archive->id}] {$archive->title}");
    }

    public function testListAll()
    {
        $pending = Todo::factory()->pending()->create();
        $done = Todo::factory()->done()->create();
        $archive = Todo::factory()->archive()->create();

        $this->artisan('ls --all')
            ->assertExitCode(0)
            ->expectsOutputToContain('Pending Todos')
            ->expectsOutputToContain("[{$pending->id}] {$pending->title}")
            ->expectsOutputToContain('Done Todos')
            ->expectsOutputToContain("[{$done->id}] {$done->title}")
            ->expectsOutputToContain('Archived Todos')
            ->expectsOutputToContain("[{$archive->id}] {$archive->title}");
    }
}
