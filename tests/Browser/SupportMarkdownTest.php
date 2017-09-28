<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SupportMarkdownTest extends DuskTestCase
{
    use DatabaseMigrations;

    function test_the_post_content_support_markdown()
    {
        $importantText = 'Un texto muy importante';

        $post = $this->createPost([
            'content' => "La primera parte del texto. **$importantText**. La última parte del texto"
        ]);

        $this->browse(function($browser) use($post, $importantText){
            $browser->visit($post->url)
                ->assertSeeIn('strong', $importantText);
        });
    }

    function test_the_code_in_the_post_is_escaped()
    {
        $code = "<script>alert('Sharing code')</script>";

        $post = $this->createPost([
            'content' => "`$code`. Texto normal."
        ]);

        $this->browse(function($browser) use($post, $code){
            $browser->visit($post->url)
                ->assertDontSee($code)
                ->assertSee('Texto normal')
                ->assertSee(htmlspecialchars($code, ENT_QUOTES));
        });
    }

    function test_xss_attack()
    {
        $xssAttack = "<script>alert('Malicious JS!')</script>";

        $post = $this->createPost([
            'content' => "$xssAttack. Texto normal."
        ]);

        $this->browse(function($browser) use($post, $xssAttack){
            $browser->visit($post->url)
                ->assertSee($xssAttack)
                ->assertSee('Texto normal')
                ->assertDontSee(htmlspecialchars($xssAttack, ENT_QUOTES));
        });
    }

    function test_xss_attack_with_html()
    {
        $xssAttack = "<img src='img.jpg'>";

        $post = $this->createPost([
            'content' => "$xssAttack. Texto normal."
        ]);

        $this->browse(function($browser) use($post, $xssAttack){
            $browser->visit($post->url)
                ->assertSee($xssAttack);
        });
    }
}
