<?php

use tests\FeatureTestCase;

class ShowPostTest extends FeatureTestCase
{
    public function test_a_user_can_see_the_post_details()
    {
        // Having
        $user = $this->defaultUser([
            'name'   => 'Crislin Nunez'
        ]);

        $post = $this->createPost([
            'title'      => 'Este es el titulo del poast',
            'content'    => 'Este es el contenido del post',
            'user_id'    => $user->id,
        ]);


        //When
        $this->visit($post->url)
            ->seeInElement('h1', $post->title)
            ->see($post->content)
            ->see('Crislin Nunez');
    }

    public function test_old_urls_are_redirected()
    {
        // Having
        $post = $this->createPost([
            'title'      => 'Old Title',
        ]);

        $url = $post->url;

        $post->update(['title' => 'New Title']);

        $this->visit($url)
            ->seePageIs($post->url);
    }

}
