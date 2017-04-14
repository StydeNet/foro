<?php

class CategoryFactory extends Factory
{
    public $model = \App\Category::class;

    public function data()
    {
        $name = $this->unique()->sentence;

        return [
            'name' => $name,
            'slug' => $this->slug($name),
        ];
    }
}
