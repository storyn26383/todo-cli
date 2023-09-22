<?php

namespace App\Commands;

use App\Commands\Traits\HasPath;
use App\Models\Todo;
use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\confirm;

class ExportCommand extends Command
{
    use HasPath;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'export {path} {--f|force : Force overwrite existing file}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Export todos';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $path = $this->normalizePath($this->argument('path'));

        $directory = dirname($path);

        if (! realpath($directory)) {
            $this->error("Directory `{$directory}` does not exist.");

            return 1;
        }

        if (! $this->option('force') &&
            realpath($path) &&
            ! confirm("File `{$path}` already exists. Do you wish to overwrite it?", default: false)
        ) {
            return 1;
        }

        file_put_contents($path, Todo::all()->toJson());

        return 0;
    }
}
