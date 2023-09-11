<?php

namespace App\Commands;

use App\Models\Todo;
use LaravelZero\Framework\Commands\Command;

class DoneCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'done';

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
        $todos = Todo::pending()->get()->map(function ($todo) {
            return [
                $todo->id,
                $todo->title,
                $todo->deadline?->diffForHumans() ?? '-',
                $todo->created_at->diffForHumans(),
            ];
        })->toArray();

        $this->table(
            ['ID', 'Title', 'Deadline', 'Created'],
            $todos
        );

        $id = $this->ask('Which todo do you want to mark as done?');

        Todo::findOrFail($id)->markAsDone();

        $this->call('ls');
    }
}
