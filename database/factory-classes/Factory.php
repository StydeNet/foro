<?php

use Faker\Generator as Faker;

class Factory
{
    public static $factory;

    public static $currentStates = [];

    /**
    * @var \Faker\Generator $faker
    */
    public $faker;

    public static function create(...$params)
    {
        $modelFactory = new static;

        static::$factory->define(
            $modelFactory->model,
            function (Faker $faker) use ($modelFactory) {
                $modelFactory->faker = $faker;

                return $modelFactory->data($faker);
            }
        );

        $factory = factory($modelFactory->model);

        $attributes = [];

        foreach ($params as $param)
        {
            if (is_integer($param)) {
                $factory->times($param);
            } else {
                $attributes = array_merge(
                    $attributes,
                    $modelFactory->getAttributes($param)
                );
            }
        }

        return $factory->create($attributes);
    }

    public static function id()
    {
        return function () {
            return static::create()->id;
        };
    }

    public function getAttributes($param)
    {
        if (is_array($param)) {
            return $param;
        }

        $state = 'state'.ucfirst($param);

        if (! method_exists($this, $state)) {
            throw new \Exception(
                "The state {$state} does not exist in the factory: ".get_class($this)
            );
        }

        return $this->$state();
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
