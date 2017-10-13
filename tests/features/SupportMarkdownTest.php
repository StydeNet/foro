<?php

class SupportMarkdownTest extends FeatureTestCase
{
    function test_the_post_content_and_comment_support_markdown()
    {
        $importantText = 'Un texto muy importante';
        $importantTextInComment = 'Otro texto muy importante';

        $post = $this->createPost([
            'content' => "La primera parte del texto. **$importantText**. La última parte del texto"
        ]);

        factory(\App\Comment::class)->create([
            'post_id' => $post->id,
            'comment' => "Este comentario tambien tiene **$importantTextInComment**."
        ]);

        $this->visit($post->url)
            ->seeInElement('strong', $importantText)
            ->seeInElement('strong', $importantTextInComment);
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
            ->seeText($xssAttack); //todo: fix this!
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
}
