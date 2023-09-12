<?php

namespace App\Commands;

use App\Enums\TodoState;
use App\Models\Todo;
use LaravelZero\Framework\Commands\Command;

class ListCommand extends Command
{
    use Helpers;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'ls {--a|all : List all todos} {--d|done : List done todos}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'List pending todos';

    /**
     * The console command name aliases.
     *
     * @var array
     */
    protected $aliases = ['l'];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->option('all')) {
            return $this->renderTodos();
        }

        if ($this->option('done')) {
            return $this->renderTodos(TodoState::Done);
        }

        return $this->renderTodos(TodoState::Pending);
    }
}
