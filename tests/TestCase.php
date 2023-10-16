<?php

namespace Tests;

use Laravel\Prompts\ConfirmPrompt;
use Laravel\Prompts\Prompt;
use Laravel\Prompts\SelectPrompt;
use Laravel\Prompts\TextPrompt;
use LaravelZero\Framework\Testing\TestCase as BaseTestCase;
use RuntimeException;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockPrompts();
    }

    public function artisan($command, $parameters = [])
    {
        if (! $this->mockConsoleOutput) {
            return $this->app[Kernel::class]->call($command, $parameters);
        }

        return new PendingCommand($this, $this->app, $command, $parameters);
    }

    private function mockPrompts()
    {
        Prompt::fallbackWhen(true);

        $this->mockTextPrompt();
        $this->mockSelectPrompt();
        $this->mockConfirmPrompt();
    }

    private function mockTextPrompt()
    {
        TextPrompt::fallbackUsing(function (TextPrompt $prompt) {
            $question = (new Question($prompt->label, $prompt->default ?: null))
                ->setValidator($this->getPromptValidator($prompt));

            $this->app[OutputStyle::class]->askQuestion($question);
        });
    }

    private function mockSelectPrompt()
    {
        SelectPrompt::fallbackUsing(function (SelectPrompt $prompt) {
            $question = (new ChoiceQuestion($prompt->label, $prompt->options, $prompt->default ?: null))
                ->setValidator($this->getPromptValidator($prompt));

            $this->app[OutputStyle::class]->askQuestion($question);
        });
    }

    private function mockConfirmPrompt()
    {
        ConfirmPrompt::fallbackUsing(function (ConfirmPrompt $prompt) {
            $question = (new ConfirmationQuestion($prompt->label, $prompt->default ?: null))
                ->setValidator($this->getPromptValidator($prompt));

            $this->app[OutputStyle::class]->askQuestion($question);
        });
    }

    private function getPromptValidator(TextPrompt|SelectPrompt|ConfirmPrompt $prompt)
    {
        return function ($answer) use ($prompt) {
            if ($prompt->required && $answer === null) {
                throw new RuntimeException(is_string($prompt->required) ? $prompt->required : 'Required.');
            }

            if ($prompt->validate) {
                $error = ($prompt->validate)($answer ?? '');

                if ($error) {
                    throw new RuntimeException($error);
                }
            }

            return $answer;
        };
    }
}
