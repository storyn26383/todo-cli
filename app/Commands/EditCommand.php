<?php

namespace App\Commands;

use App\Models\Todo;
use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class EditCommand extends Command
{
    use Helpers;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'edit {id?} {title?}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Edit a todo';

    /**
     * The console command name aliases.
     *
     * @var array
     */
    protected $aliases = ['e'];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $id = $this->argument('id') ?? select(
            label: 'Which todo do you want to edit?',
            options: Todo::pluck('title', 'id'),
        );

        $todo = Todo::findOrFail($id);

        $title = $this->argument('title') ?? text(
            label: 'What is the new title?',
            default: $todo->title
        );

        $todo->update(compact('title'));

        return $this->renderTodos();
    }
}
