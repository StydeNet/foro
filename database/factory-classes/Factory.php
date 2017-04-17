<?php

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\FactoryBuilder;

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
        $this->faker = app(Faker::class);

        $definitions = [
            $this->model() => [
                'default' => function (Faker $faker) {
                    return $this->data($faker);
                }
            ]
        ];

        $states = [];

        $this->builder = new FactoryBuilder(
            $this->model(), 'default', $definitions, $states, $this->faker
        );
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
