<?php

namespace App\Repositories;

use App\Models\Tasks as Model;
use App\Repositories\CoreRepository;
use Illuminate\Support\Facades\Validator;

class TasksRepository extends CoreRepository
{
    /**
     * @return string
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    public function validator($data, $params)
    {
        $validator = Validator::make($data, $params);

        if ($validator->fails()) {
            header("HTTP/1.1 400");
            header('Content-Type: application/json');
            header("Access-Control-Allow-Origin: *");

            echo json_encode($validator->errors(), 400);
            exit;
        }

        $validatedData = $validator->validated();
        
        return $validatedData;
    }

    public function getValidate($data)
    {
        return $this->validator($data, [
            'order_by'     => 'required|string',
            'order_type'   => 'required|string',
            'board_id'     => 'required|int',
            'label_ids'    => 'array|exists:labels,id',
            'statuses_ids' => 'array|exists:task_statuses,id'
        ]);
    }

    public function totalTaskValidate($data)
    {
        return $this->validator($data, [
            'board_id'     => 'required|int',
        ]);
    }

    public function storeValidate($data)
    {
        return $this->validator($data, [
            'title' => 'required|string|min:3|max:150',
            'status_id' => 'int',
            'board_id' => 'required|int|exists:boards,id',
            'image_link' => 'string',
        ]);
    }

    public function paginate($params, $per_page=10, $order_by='id', $order_type="desc")
    {
        $in_statements = $params['IN_STATEMENTS'] ?? [];
        if(isset($params['IN_STATEMENTS'])) unset($params['IN_STATEMENTS']);

        $result = $this->startConditions()->where($params);

        foreach ($in_statements as $key => $value) {
            $result = $result->whereIn($key, $value);
        }

        $result = $result->orderBy($order_by, $order_type)
                         ->paginate($per_page);

        return $result;
    }

    public function create($data)
    {
        $result = $this->startConditions()->create($data);
        return $result;
    }

    public function update($data, $params)
    {
        $user = $this->startConditions()->where($params);
        $result = $user->update($data);
        return $result;
    }

    public function find($params)
    {
        $task = $this->startConditions()->where($params)->first();
        return $task;
    }

    public function findAll($params)
    {
        $task = $this->startConditions()->where($params)->get();
        return $task;
    }

}