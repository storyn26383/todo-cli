<?php

namespace App\Commands;

use App\Enums\TodoState;
use App\Models\Todo;
use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\select;

class ArchiveCommand extends Command
{
    use Helpers;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'archive {id?}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Mark a todo as archived';

    /**
     * The console command name aliases.
     *
     * @var array
     */
    protected $aliases = ['a'];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $id = $this->argument('id') ?? select(
            label: 'Which todo do you want to mark as archived?',
            options: Todo::pending()->pluck('title', 'id'),
            scroll: 15,
        );

        Todo::findOrFail($id)->markAsArchived();

        return $this->renderTodos(TodoState::ALL);
    }
}
