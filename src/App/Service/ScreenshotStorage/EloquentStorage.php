<?php

namespace App\Service\ScreenshotStorage;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class EloquentStorage implements StorageInterface
{
    /**
     * @var Model
     */
    private $model;

    public function __construct(Model $model)
    {
        $this->model = $model->newInstance();
    }

    /**
     * @param \stdClass $data
     * @return bool
     */
    public function store(\stdClass $data)
    {
        $this->model->fill([
            'name' => $data->name,
            'is_new' => true,
            'shooted_at' => Carbon::now(),
        ]);

        $result = $this->model->save();

        return $result;
    }
}