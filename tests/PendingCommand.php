<?php

namespace Tests;

use Illuminate\Console\OutputStyle;
use Illuminate\Testing\PendingCommand as BasePendingCommand;
use Mockery;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class PendingCommand extends BasePendingCommand
{
    protected function mockConsoleOutput()
    {
        $mock = Mockery::mock(OutputStyle::class.'[askQuestion]', [
            new ArrayInput($this->parameters), $this->createABufferedOutputMock(),
        ]);

        foreach ($this->test->expectedQuestions as $i => $question) {
            $mock->shouldReceive('askQuestion')
                ->once()
                ->ordered()
                ->with(Mockery::on(function ($argument) use ($question) {
                    if (isset($this->test->expectedChoices[$question[0]])) {
                        $this->test->expectedChoices[$question[0]]['actual'] = $argument->getAutocompleterValues();
                    }

                    return $argument->getQuestion() == $question[0];
                }))
                ->andReturnUsing(function () use ($question, $i) {
                    unset($this->test->expectedQuestions[$i]);

                    return $question[1];
                });
        }

        $this->app->bind(OutputStyle::class, function () use ($mock) {
            return $mock;
        });

        return $mock;
    }

    private function createABufferedOutputMock()
    {
        $mock = Mockery::mock(BufferedOutput::class.'[doWrite]')
            ->shouldAllowMockingProtectedMethods()
            ->shouldIgnoreMissing();

        $mock->shouldReceive('doWrite')
            ->withArgs(function ($output) {
                foreach ($this->test->expectedOutput as $i => $text) {
                    if ($output === $text) {
                        unset($this->test->expectedOutput[$i]);
                    }
                }

                foreach ($this->test->expectedOutputSubstrings as $i => $text) {
                    if (str_contains($output, $text)) {
                        unset($this->test->expectedOutputSubstrings[$i]);
                    }
                }

                foreach ($this->test->unexpectedOutput as $text => $displayed) {
                    if ($output === $text) {
                        $this->test->unexpectedOutput[$text] = true;
                    }
                }

                foreach ($this->test->unexpectedOutputSubstrings as $text => $displayed) {
                    if (str_contains($output, $text)) {
                        $this->test->unexpectedOutputSubstrings[$text] = true;
                    }
                }
            });

        return $mock;
    }
}
