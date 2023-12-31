<?php

namespace App\Commands;

use App\Commands\Traits\RenderTodos;
use App\Enums\TodoState;
use App\Models\Todo;
use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\select;

class RemoveCommand extends Command
{
    use RenderTodos;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'rm {id?}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Remove a todo';

    /**
     * The console command name aliases.
     *
     * @var array
     */
    protected $aliases = ['r'];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $id = $this->argument('id') ?? select(
            label: 'Which todo do you want to remove?',
            options: Todo::pluck('title', 'id'),
            scroll: 15,
        );

        $todo = Todo::findOrFail($id);

        if (! confirm("Todo `[{$id}] {$todo->title}` will be removed. Do you wish to continue?", default: false)) {
            return 1;
        }

        $todo->delete();

        return $this->renderTodos(TodoState::ALL);
    }
}
