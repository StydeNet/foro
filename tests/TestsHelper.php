<?php

namespace Tests;

use App\{Post, User};

trait TestsHelper
{
    /**
     * @var \App\User
     */
    protected $defaultUser;

    public function defaultUser(array $attributes = [])
    {
        if ($this->defaultUser) {
            return $this->defaultUser;
        }

        return $this->defaultUser = \UserFactory::create($attributes);
    }

    protected function createPost(array $attributes = [])
    {
        return \PostFactory::create($attributes);
    }

    protected function anyone(array $attributes = [])
    {
        return \UserFactory::create($attributes);
    }

    protected function actingAsAnyone(array $attributes = [])
    {
        $user = $this->anyone($attributes);
        $this->actingAs($user);
        return $user;
    }
}
