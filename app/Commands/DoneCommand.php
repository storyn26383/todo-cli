<?php

namespace App\Commands;

use App\Models\Todo;
use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\select;

class DoneCommand extends Command
{
    use Helpers;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'done {id?}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Mark a todo as done';

    /**
     * The console command name aliases.
     *
     * @var array
     */
    protected $aliases = ['d'];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $id = $this->argument('id') ?? select(
            label: 'Which todo do you want to mark as done?',
            options: Todo::pending()->pluck('title', 'id'),
        );

        Todo::findOrFail($id)->markAsDone();

        return $this->renderTodos();
    }
}
