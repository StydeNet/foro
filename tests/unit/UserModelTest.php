<?php

use App\{  User, Post };
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserModelTest extends TestCase
{
    function test_a_post_belongs_to_user()
    {
    	$user = $this->defaultUSer();

        $firstPost = factory(Post::class)->create([
        	'user_id' => $user->id
        ]);

        $secondPost = factory(Post::class)->create();

        $this->assertTrue($user->owns($firstPost));

        $this->assertFalse($user->owns($secondPost));
    }
}
