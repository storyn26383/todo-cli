<?php

namespace App\Commands;

use App\Commands\Traits\HasPath;
use App\Models\Todo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;
use RuntimeException;
use Throwable;

use function Laravel\Prompts\confirm;

class ImportCommand extends Command
{
    use HasPath;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'import {path}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Import todos';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $contents = $this->getContents();
            $data = json_decode($contents, true);

            if (! $data) {
                throw new RuntimeException('Invalid JSON provided.');
            }

            if (! confirm('Are you sure you want to import these todos?', default: false)) {
                return 1;
            }

            Todo::insert($this->formatData($data));
        } catch (Throwable $e) {
            $this->error($e->getMessage());

            return 1;
        }

        return 0;
    }

    private function getContents(): string
    {
        $path = $this->argument('path');

        if ($path) {
            return $this->getContentsFromFile($path);
        }

        // FIXME: stdin break confirm method
        return $this->getContentsFromStdin();
    }

    private function getContentsFromFile(string $path): string
    {
        $path = $this->normalizePath($path);

        if (! File::exists($path)) {
            throw new RuntimeException("File `{$path}` does not exist.");
        }

        return File::get($path);
    }

    private function getContentsFromStdin(): string
    {
        $stdin = [STDIN];
        $null = null;

        if (! stream_select($stdin, $null, $null, 0)) {
            throw new RuntimeException('No input provided.');
        }

        $contents = trim(fgets(STDIN));

        if (! $contents) {
            throw new RuntimeException('No input provided.');
        }

        return $contents;
    }

    private function formatData(array $data): array
    {
        return array_map(
            fn ($row) => (new Todo)->forceFill(Arr::except($row, ['id']))->getAttributes(),
            $data
        );
    }
}
