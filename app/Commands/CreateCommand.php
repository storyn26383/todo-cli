<?php

namespace App\Commands;

use App\Models\Todo;
use LaravelZero\Framework\Commands\Command;

class CreateCommand extends Command
{
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
        $title = $this->argument('title') ?? $this->ask('What is the title of the todo?');

        Todo::create(compact('title'));

        $this->call('ls');
    }
}
