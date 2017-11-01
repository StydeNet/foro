<?php

use App\User;
use Tests\TestsHelper;
use Tests\CreatesApplication;

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    use CreatesApplication, TestsHelper;

    public function setUp()
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }
}
