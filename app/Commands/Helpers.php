<?php

namespace App\Commands;

use App\Enums\TodoState;
use App\Models\Todo;
use Illuminate\Support\Facades\Blade;
use Termwind\HtmlRenderer;

trait Helpers
{
    private function renderTodos(?TodoState $state = null)
    {
        $query = Todo::query();

        if ($state === TodoState::Pending) {
            $query->pending();
        }

        if ($state === TodoState::Done) {
            $query->done();
        }

        $todos = $query->get()->groupBy('state')->map->map(function ($todo) {
            return (object) [
                'id' => $todo->id,
                'title' => $todo->title,
                'deadline' => $todo->deadline?->diffForHumans() ?? '-',
                'created_at' => $todo->created_at->diffForHumans(),
            ];
        });

        $html = Blade::render(<<<HTML
            <div class="space-y-1">
                {$this->buildTodosHtml(TodoState::Pending->value, 'bg-blue')}
                {$this->buildTodosHtml(TodoState::Done->value, 'bg-green')}
            </div>
        HTML, compact('state', 'todos'));

        // FIXME: render function not working in phar file
        // render($html);
        $this->line((new HtmlRenderer)->parse($html)->toString());

        return 0;
    }

    private function buildTodosHtml(string $state, string $titleBg): string
    {
        $title = ucfirst($state);

        return <<<HTML
            @if (isset(\$todos['{$state}']))
                <div>
                    <div class="px-1 {$titleBg} text-black font-bold">{$title} Todos</div>
                    @foreach (\$todos['{$state}'] as \$todo)
                        <div class="flex space-x-1">
                            <span>[{{ \$todo->id }}]</span>
                            <span>{{ \$todo->title }}</span>
                            <span class="flex-1 content-repeat-[.] text-gray"></span>
                            <span>{{ \$todo->created_at }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        HTML;
    }
}
