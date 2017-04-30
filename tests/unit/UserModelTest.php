<?php

use App\{Post, User};

use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserModelTest extends TestCase
{
    use DatabaseTransactions;

    function test_the_model_is_owned_by_the_user()
    {
        $user = factory(User::class)->create();

        $post = factory(Post::class)->create([
            'user_id' => $user->id,
        ]);

        $this->assertTrue($user->owns($post));
    }

    function test_the_model_is_not_owned_by_the_user()
    {
        $user = factory(User::class)->create();

        $post = factory(Post::class)->create();

        $this->assertFalse($user->owns($post));
    }
}
