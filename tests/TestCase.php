<?php

use App\User;
use Tests\TestsHelper;
use Tests\CreatesApplication;

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    use CreatesApplication, TestsHelper;

    protected function createComment(array $attributes = [])
    {
        return factory(\App\Comment::class)->create($attributes);
    }
}
