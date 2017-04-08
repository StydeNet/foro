<?php

use App\Post;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreatePostMethodTest extends TestCase
{
	use DatabaseTransactions;
	
    function test_the_create_post_method_works_correctly()
    {
    	$user = $this->defaultUser();

    	$post = factory(Post::class)->make();

    	$user->createPost($post->getAttributes());

    	$this->seeInDatabase('posts', [
    		'user_id' => $user->id
    	]);
    }
}
