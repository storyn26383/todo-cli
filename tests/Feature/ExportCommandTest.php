<?php

namespace Tests\Feature;

use App\Models\Todo;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ExportCommandTest extends TestCase
{
    use DatabaseMigrations;

    public function testExport()
    {
        Todo::factory()->times(10)->create();

        $filename = 'test.json';
        $dirname = getcwd();
        $path = "{$dirname}/{$filename}";

        File::shouldReceive('dirname')->once()->with($path)->andReturn($dirname);
        File::shouldReceive('exists')->once()->with($dirname)->andReturn(true);
        File::shouldReceive('exists')->once()->with($path)->andReturn(false);
        File::shouldReceive('put')->once()->with($path, Todo::all()->toJson());

        $this->artisan("export {$filename}")
            ->assertExitCode(0);
    }

    public function testExportWhenFileExists()
    {
        Todo::factory()->times(10)->create();

        $filename = 'test.json';
        $dirname = getcwd();
        $path = "{$dirname}/{$filename}";

        File::shouldReceive('dirname')->once()->with($path)->andReturn($dirname);
        File::shouldReceive('exists')->once()->with($dirname)->andReturn(true);
        File::shouldReceive('exists')->once()->with($path)->andReturn(true);
        File::shouldReceive('put')->once()->with($path, Todo::all()->toJson());

        $this->artisan("export {$filename}")
            ->assertExitCode(0)
            ->expectsConfirmation("File `{$path}` already exists. Do you wish to overwrite it?", 'yes');
    }

    public function testNotOverwrite()
    {
        $filename = 'test.json';
        $dirname = getcwd();
        $path = "{$dirname}/{$filename}";

        File::shouldReceive('dirname')->once()->with($path)->andReturn($dirname);
        File::shouldReceive('exists')->once()->with($dirname)->andReturn(true);
        File::shouldReceive('exists')->once()->with($path)->andReturn(true);

        $this->artisan("export {$filename}")
            ->assertExitCode(1)
            ->expectsConfirmation("File `{$path}` already exists. Do you wish to overwrite it?", 'no');
    }

    public function testForceExport()
    {
        Todo::factory()->times(10)->create();

        $filename = 'test.json';
        $dirname = getcwd();
        $path = "{$dirname}/{$filename}";

        File::shouldReceive('dirname')->once()->with($path)->andReturn($dirname);
        File::shouldReceive('exists')->once()->with($dirname)->andReturn(true);
        File::shouldReceive('put')->once()->with($path, Todo::all()->toJson());

        $this->artisan("export {$filename} --force")
            ->assertExitCode(0);
    }

    public function testAbsolutePath()
    {
        Todo::factory()->times(10)->create();

        $dirname = '/test';
        $path = "{$dirname}/test.json";

        File::shouldReceive('dirname')->once()->with($path)->andReturn($dirname);
        File::shouldReceive('exists')->once()->with($dirname)->andReturn(true);
        File::shouldReceive('exists')->once()->with($path)->andReturn(false);
        File::shouldReceive('put')->once()->with($path, Todo::all()->toJson());

        $this->artisan("export {$path}")
            ->assertExitCode(0);
    }

    public function testDirectoryNotExists()
    {
        $dirname = '/test';
        $path = "{$dirname}/test.json";

        File::shouldReceive('dirname')->once()->with($path)->andReturn($dirname);
        File::shouldReceive('exists')->once()->with($dirname)->andReturn(false);

        $this->artisan("export {$path}")
            ->assertExitCode(1)
            ->expectsOutputToContain("Directory `{$dirname}` does not exist.");
    }
}
