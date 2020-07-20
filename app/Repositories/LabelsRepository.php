<?php

namespace App\Repositories;

use App\Models\Labels as Model;
use App\Repositories\CoreRepository;
use Illuminate\Support\Facades\Validator;

class LabelsRepository extends CoreRepository
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
            'order_type' => 'required|string'
        ]);
    }

    public function storeValidate($data)
    {
        return $this->validator($data, [
            'title' => 'required|string|min:3|max:50',
            'user_id' => 'required|int|exists:tasks,id'
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

}