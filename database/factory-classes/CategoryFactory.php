<?php

use App\Category;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function data()
    {
        $name = $this->unique()->sentence;

        return [
            'name' => $name,
            'slug' => str_slug($name),
        ];
    }
}
