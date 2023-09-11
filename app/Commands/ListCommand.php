<?php

namespace App\Commands;

use App\Models\Todo;
use LaravelZero\Framework\Commands\Command;

class ListCommand extends Command
{
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
        [$headers, $todos] = $this->getTodos();

        $this->table($headers, $todos);
    }

    private function getTodos(): array
    {
        if ($this->option('all')) {
            return $this->getAllTodos();
        }

        if ($this->option('done')) {
            return $this->getDoneTodos();
        }

        return $this->getPendingTodos();
    }

    private function getAllTodos(): array
    {
        $headers = ['Title', 'State', 'Deadline', 'Created'];
        $todos = Todo::get()->map(function ($todo) {
            return [
                $todo->title,
                $todo->state->value,
                $todo->deadline?->diffForHumans() ?? '-',
                $todo->created_at->diffForHumans(),
            ];
        })->toArray();

        return [$headers, $todos];
    }

    private function getPendingTodos(): array
    {
        $headers = ['Title', 'Deadline', 'Created'];
        $todos = Todo::pending()->get()->map(function ($todo) {
            return [
                $todo->title,
                $todo->deadline?->diffForHumans() ?? '-',
                $todo->created_at->diffForHumans(),
            ];
        })->toArray();

        return [$headers, $todos];
    }

    private function getDoneTodos(): array
    {
        $headers = ['Title', 'Deadline', 'Created'];
        $todos = Todo::done()->get()->map(function ($todo) {
            return [
                $todo->title,
                $todo->deadline?->diffForHumans() ?? '-',
                $todo->created_at->diffForHumans(),
            ];
        })->toArray();

        return [$headers, $todos];
    }
}
