<?php
use App\Post;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserModelTest extends TestCase
{
    use DatabaseMigrations;
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

    /** @test */
    function test_a_user_is_a_owner()
    {
        $user= factory(\App\User::class)->create(['id' => 1]);

        $post =  factory(\App\Post::class)->create([
            'user_id' => $user->id
        ]);

        $this->assertTrue($user->owns($post));
    }

    /** @test */
    function test_a_user_is_not_owner()
    {
        $not_owner= factory(\App\User::class)->create(['id' => 1]);
        $owner = factory(\App\User::class)->create(['id' => 2]);

        $post =  factory(\App\Post::class)->create([
            'user_id' => $owner->id
        ]);

        $this->assertFalse($not_owner->owns($post));
    }
}
