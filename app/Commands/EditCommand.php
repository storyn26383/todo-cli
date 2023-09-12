<?php

namespace App\Commands;

use App\Models\Todo;
use LaravelZero\Framework\Commands\Command;

class EditCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'edit';

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

        $id = $this->ask('Which todo do you want to edit?');
        $todo = Todo::findOrFail($id);
        $title = $this->ask('What is the new title?', $todo->title);

        $todo->update(compact('title'));

        $this->call('ls', ['--all' => true]);
    }
}
