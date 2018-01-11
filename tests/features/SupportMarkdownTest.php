<?php

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
    
    //para los comentarios

    function test_5_comments_support_markdown()
    {
        $importantText = "Texto muy importante";

        $post = $this->createPost();

        $comment = factory(Comment::class)->create([
            'comment' => "La primera parte del texto. **$importantText**. La última parte del texto.",
            'post_id' => $post->id,
        ]);

        $comment->save();

        $this->visit( $comment->post->url )
            ->seeInElement('strong', $importantText );
    }

    function test_6_the_code_in_the_comment_is_escaped()
    {
        $xssAttack = "<script>alert('Sharing Code!')</script>";

        $post = $this->createPost();

        $comment = factory(Comment::class)->create([
            'comment' => "$xssAttack. Normal text.",
            'post_id' => $post->id,
        ]);

        $comment->save();

        $this->visit( $comment->post->url )
            ->dontSee( $xssAttack )
            ->seeText( 'Normal text.')
            ->seeText( $xssAttack );
    }

    function test_7_xss_attack_in_comment()
    {
        $xssAttack = "<script>alert('Malicius JS!')</script>";

        $post = $this->createPost();

        $comment = factory(Comment::class)->create([
            'comment' => "$xssAttack. Normal text.",
            'post_id' => $post->id,
        ]);

        $comment->save();

        $this->visit( $comment->post->url )
            ->dontSee( $xssAttack )
            ->seeText('Normal text');
    }

    function test_8_xss_attack_with_html_in_comment()
    {
        $xssAttack = "<img src='img.jpeg'>";

        $post = $this->createPost();

        $comment = factory(Comment::class)->create([
            'comment' => "$xssAttack. Normal text.",
            'post_id' => $post->id,
        ]);

        $comment->save();

        $this->visit( $comment->post->url )
            ->dontSee( $xssAttack );
    }
}
