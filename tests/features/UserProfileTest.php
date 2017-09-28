<?php
use App\Post;

class UserProfileTest extends FeatureTestCase
{
    function test_a_user_can_see_an_user_profile()
    {
        $user = $this->defaultUser();

        $post = $this->createPost([
            'user_id' => $user->id,
        ]);

        $this->visitRoute('users.show', $user)
            ->seeInElement('h1', "Perfil de {$user->name}")
            ->see($user->username)
            ->see($post->title);
    }
}
