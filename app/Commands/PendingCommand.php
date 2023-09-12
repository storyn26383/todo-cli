<?php

namespace App\Commands;

use App\Models\Todo;
use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\select;

class PendingCommand extends Command
{
    use Helpers;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'pending {id?}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Mark a todo as pending';

    /**
     * The console command name aliases.
     *
     * @var array
     */
    protected $aliases = ['p'];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $id = $this->argument('id') ?? select(
            label: 'Which todo do you want to mark as pending?',
            options: Todo::done()->pluck('title', 'id'),
        );

        Todo::findOrFail($id)->markAsPending();

        return $this->renderTodos();
    }
}
