<?php

use Faker\Generator as Faker;

class Factory
{
    public static $factory;

    /**
    * @var \Faker\Generator $faker
    */
    public $faker;

    public static function create(array $attributes = [])
    {
        $modelFactory = new static;

        static::$factory->define(
            $modelFactory->model,
            function (Faker $faker) use ($modelFactory) {
                $modelFactory->faker = $faker;

                return $modelFactory->data($faker);
            }
        );

        return factory($modelFactory->model)->create($attributes);
    }

    public static function id()
    {
        return function () {
            return static::create()->id;
        };
    }

    public function randomString($length = 100)
    {
        return str_random($length);
    }

    public function slug($value)
    {
        return str_slug($value);
    }

    public function __get($var)
    {
        return $this->faker->$var;
    }

    public function __call($method, array $attributes = [])
    {
        return $this->faker->$method(...$attributes);
    }
}
