<?php

namespace App\Commands;

use App\Commands\Traits\HasPath;
use App\Models\Todo;
use Illuminate\Support\Facades\File;
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

        $directory = File::dirname($path);

        if (! File::exists($directory)) {
            $this->error("Directory `{$directory}` does not exist.");

            return 1;
        }

        if (! $this->option('force') &&
            File::exists($path) &&
            ! confirm("File `{$path}` already exists. Do you wish to overwrite it?", default: false)
        ) {
            return 1;
        }

        File::put($path, Todo::all()->toJson());

        return 0;
    }
}
