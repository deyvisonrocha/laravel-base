<?php

namespace App\Repositories;

use Exception;
use Illuminate\Container\Container as Application;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class BaseRepository
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Model
     */
    protected $model;

    protected $paginationLimit = 10;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->makeModel();
        $this->boot();
    }

    /**
     *
     */
    public function boot()
    {

    }

    abstract public function model();

    /**
     * @return Model
     * @throws Exception
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

     /**
     * @throws Exception
     */
    public function resetModel()
    {
        $this->makeModel();
    }

    /**
     * Find data by id
     *
     * @param       $id
     * @param array $columns
     *
     * @return mixed
     */
    public function find($id, $columns = ['*'])
    {
        $model = $this->model->find($id, $columns);

        if (is_null($model)) {
            throw new ModelNotFoundException;
        }

        $this->resetModel();

        return $model;
    }

    /**
     * Save a new entity in repository
     *
     * @param array $attributes
     *
     * @return mixed
     */
    public function create(array $attributes)
    {
        $model = $this->model->newInstance($attributes);
        $model->save();
        $this->resetModel();

        return $model;
    }

    /**
     * Update a entity in repository by id
     *
     * @param array $attributes
     * @param       $id
     *
     * @return mixed
     */
    public function update(array $attributes, $id)
    {
        $model = $this->model->find($id);
        $model->fill($attributes);
        $model->save();
        $this->resetModel();

        return $model;
    }

    /**
     * Delete a entity in repository by id
     *
     * @param $id
     *
     * @return int
     */
    public function delete($id)
    {
        $model = $this->find($id);
        $this->resetModel();

        $deleted = $model->delete();

        return $model->delete();
    }

    /**
     * Retrieve all data of repository, paginated
     *
     * @param null   $limit
     * @param array  $columns
     * @param string $method
     *
     * @return mixed
     */
    public function paginate($limit = null, $columns = ['*'])
    {
        $limit = is_null($limit) ? $this->paginationLimit : $limit;
        $results = $this->model->paginate($limit, $columns);
        $this->resetModel();

        return $results;
    }
}
