<?php

namespace App\Commands;

use App\Models\Todo;
use LaravelZero\Framework\Commands\Command;

class RemoveCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'rm';

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
        $todos = Todo::get()->map(function ($todo) {
            return [
                $todo->id,
                $todo->title,
                $todo->state->value,
                $todo->deadline?->diffForHumans() ?? '-',
                $todo->created_at->diffForHumans(),
            ];
        })->toArray();

        $this->table(
            ['ID', 'Title', 'State', 'Deadline', 'Created'],
            $todos
        );

        $id = $this->ask('Which todo do you want to remove?');

        $todo = Todo::findOrFail($id);

        $this->confirm("Todo `[{$id}] {$todo->title}` will be removed. Do you wish to continue?");

        $todo->delete();

        $this->call('ls', ['--all' => true]);
    }
}
