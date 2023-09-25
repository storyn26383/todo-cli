<?php

namespace Tests\Feature;

use Tests\TestCase;

class InspireCommandTest extends TestCase
{
    public function testInspiresArtisans()
    {
        $this->artisan('inspire')->assertExitCode(0);
    }
}
