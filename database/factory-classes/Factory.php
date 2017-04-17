<?php

use Faker\Generator as Faker;

class Factory
{
    public static $factory;

    /**
    * @var \Faker\Generator $faker
    */
    public $faker;

    protected $model;

    protected $builder;

    public function __construct()
    {
        static::$factory->define($this->model(), function (Faker $faker) {
            $this->faker = $faker;

            return $this->data($faker);
        });

        $this->builder = new FactoryBuilder($this);
    }

    public function model()
    {
        if (!$this->model) {
            throw new \Exception('Please define a model in the '.get_class($this).' class');
        }

        return $this->model;
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

    public static function __callStatic($method, array $attributes = [])
    {
        return (new static)->builder->$method(...$attributes);
    }
}
