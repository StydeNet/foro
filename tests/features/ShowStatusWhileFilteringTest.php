<?php
use App\Post;

class ShowStatusWhileFilteringTest extends FeatureTestCase
{
    function test_display_status_in_title_while_filtering_pending_posts()
    {
        $post = $this->createPost([
            'pending' => true,
        ]);

        $this->visitRoute('posts.pending', $post->category)
            ->seeInElement('h1', "Posts pendientes de {$post->category->name}");
    }

    function test_display_status_in_title_while_filtering_completed_posts()
    {
        $post = $this->createPost([
            'pending' => false,
        ]);

        $this->visitRoute('posts.completed', $post->category)
            ->seeInElement('h1', "Posts completados de {$post->category->name}");
    }
}
