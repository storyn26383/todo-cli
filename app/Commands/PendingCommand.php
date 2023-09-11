<?php

namespace App\Commands;

use App\Models\Todo;
use LaravelZero\Framework\Commands\Command;

class PendingCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'pending';

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
        $todos = Todo::done()->get()->map(function ($todo) {
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

        $id = $this->ask('Which todo do you want to mark as pending?');

        Todo::findOrFail($id)->markAsPending();

        $this->call('ls', ['--done' => true]);
    }
}
