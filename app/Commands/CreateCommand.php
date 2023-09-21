<?php

namespace App\Commands;

use App\Enums\TodoState;
use App\Models\Todo;
use LaravelZero\Framework\Commands\Command;

use function Laravel\Prompts\text;

class CreateCommand extends Command
{
    use Helpers;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'create {title?}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create a new todo';

    /**
     * The console command name aliases.
     *
     * @var array
     */
    protected $aliases = ['c'];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $title = $this->argument('title') ?? text('What is the title of the todo?');

        Todo::create(compact('title'));

        return $this->renderTodos(TodoState::PENDING);
    }
}
