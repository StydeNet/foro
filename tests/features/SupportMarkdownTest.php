<?php

use App\Comment;
use tests\FeatureTestCase;

class SupportMarkdownTest extends FeatureTestCase
{
    function test_the_post_content_support_markdown()
    {
        $importantText = 'Un texto muy importante';

        $post = $this->createPost([
            'content' => "La primera parte del texto. **$importantText**. La última parte del texto"
        ]);

        $this->visit($post->url)
            ->seeInElement('strong', $importantText);
    }

    function test_the_code_in_the_post_is_escaped()
    {
        $code = "<script>alert('Sharing code')</script>";

        $post = $this->createPost([
            'content' => "`$code`. Texto normal."
        ]);

        $this->visit($post->url)
            ->dontSee($code)
            ->seeText('Texto normal')
            ->seeText($code);
    }

    function test_xss_attack()
    {
        $xssAttack = "<script>alert('Malicious JS!')</script>";

        $post = $this->createPost([
            'content' => "$xssAttack. Texto normal."
        ]);

        $this->visit($post->url)
            ->dontSee($xssAttack)
            ->seeText('Texto normal')
            ->seeText($xssAttack); //Todo: fix this!
    }

    function test_xss_attack_with_html()
    {
        $xssAttack = "<img src='img.jpg'>";

        $post = $this->createPost([
            'content' => "$xssAttack. Texto normal."
        ]);

        $this->visit($post->url)
            ->dontSee($xssAttack);
    }

    function test_the_post_comment_support_markdown()
    {
        $importantText = 'Laravel 5.3';

        $comment = factory(Comment::class)->create([
            'comment' => "¿Debo usar **$importantText** o 5.1 LTS?",
        ]);

        $this->visit($comment->post->url)
            ->seeInElement('strong', $importantText);
    }
}
