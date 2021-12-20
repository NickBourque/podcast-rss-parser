<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected Model $model;


    abstract protected function getModelClassName(): string;


    public function __construct()
    {
        $this->makeModel();
    }


    private function makeModel()
    {
        $this->model = app($this->getModelClassName());
    }


    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }
}
