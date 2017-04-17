<?php

class FactoryBuilder
{
    protected $factory;

    protected $model;

    public function __construct($model)
    {
        $this->factory = factory($model->model());
    }

    public function create(...$params)
    {
        $attributes = [];

        foreach ($params as $param)
        {
            if (is_integer($param)) {
                $this->times($param);
            } else {
                $attributes = array_merge($attributes, $this->attributes($param));
            }
        }

        return $this->factory->create($attributes);
    }

    public function id()
    {
        return function () {
            return $this->create()->id;
        };
    }

    public function attributes($param)
    {
        if (is_array($param)) {
            return $param;
        }

        $state = 'state'.ucfirst($param);

        if (! method_exists($this->model, $state)) {
            throw new \Exception(
                "The state {$state} does not exist in the factory: ".get_class($this->model)
            );
        }

        return $this->$state();
    }

    public function times($amount)
    {
        $this->factory->times($amount);
    }
}
