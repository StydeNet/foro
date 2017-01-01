<?php
use App\Post;
use App\User;

class UserModelTest extends TestCase
{
    function test_user_owner_of_model_owns_the_model()
    {
        $user = new User();
        $user->id = 1;

        $post = new Post();
        $post->user_id = $user->id;

        $this->assertTrue($user->owns($post));
    }

    function test_user_non_owner_of_model_does_not_own_the_model()
    {
        $user = new User();
        $user->id = 1;

        $post = new Post();
        $post->user_id = 2;

        $this->assertFalse($user->owns($post));
    }
}
