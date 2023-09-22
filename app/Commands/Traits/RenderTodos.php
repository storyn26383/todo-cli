<?php

namespace App\Commands\Traits;

use App\Enums\TodoState;
use App\Models\Todo;
use Illuminate\Support\Facades\Blade;
use Termwind\HtmlRenderer;

trait RenderTodos
{
    private function renderTodos(int $state = TodoState::ALL)
    {
        $todos = Todo::where('state', '&', $state)->get()->groupBy('state')->map->map(function ($todo) {
            return (object) [
                'id' => $todo->id,
                'title' => $todo->title,
                'deadline' => $todo->deadline?->diffForHumans() ?? '-',
                'created_at' => $todo->created_at->diffForHumans(),
            ];
        });

        $html = Blade::render(<<<HTML
            <div class="space-y-1">
                {$this->buildTodosHtml(TodoState::PENDING, 'bg-blue-400 text-black')}
                {$this->buildTodosHtml(TodoState::DONE, 'bg-green-400 text-black')}
                {$this->buildTodosHtml(TodoState::ARCHIVED, 'bg-gray text-black')}
            </div>
        HTML, compact('state', 'todos'));

        // FIXME: render function not working in phar file
        // render($html);
        $this->line((new HtmlRenderer)->parse($html)->toString());

        return 0;
    }

    private function buildTodosHtml(string $state, string $titleStyle): string
    {
        $titleMapping = [
            TodoState::PENDING => 'Pending',
            TodoState::DONE => 'Done',
            TodoState::ARCHIVED => 'Archived',
        ];

        $title = $titleMapping[$state];

        return <<<HTML
            @if (isset(\$todos['{$state}']))
                <div>
                    <div class="px-1 {$titleStyle}">{$title} Todos</div>
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
