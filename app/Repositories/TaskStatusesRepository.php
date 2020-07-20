<?php

namespace App\Repositories;

use App\Models\TaskStatuses as Model;
use App\Repositories\CoreRepository;
use Illuminate\Support\Facades\Validator;

class TaskStatusesRepository extends CoreRepository
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
            'order_by' => 'required|string',
            'order_type' => 'required|string',
            'board_id' => 'required|int|exists:boards,id'
        ]);
    }

    public function storeValidate($data)
    {
        return $this->validator($data, [
            'title' => 'required|string|min:3|max:50',
            'board_id' => 'required|int|exists:boards,id'
        ]);
    }

    public function paginate($params, $per_page=10, $order_by='id', $order_type="desc")
    {
        $result = $this->startConditions()
                    ->where($params)
                    ->orderBy($order_by, $order_type)
                    ->paginate($per_page);

        return $result;
    }

    public function update($data, $params)
    {
        $user = $this->startConditions()->where($params);
        $result = $user->update($data);
        return $result;
    }

    public function create($data)
    {
        $result = $this->startConditions()->create($data);
        return $result;
    }

    public function multiple_create($data)
    {
        $result = $this->startConditions()->insert($data);
        return $result;
    }

    public function find($params)
    {
        $statusRow = $this->startConditions()->where($params)->first();
        return $statusRow;
    }

    public function findAll($params)
    {
        $statusRow = $this->startConditions()->where($params)->get();
        return $statusRow;
    }

    public function deleteByID($id) {
        $result = $this->startConditions()->findOrFail($id)->delete();
        return $result;
    }

}