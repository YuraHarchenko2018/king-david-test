<?php

namespace App\Repositories;

use App\Models\TaskLabels as Model;
use App\Repositories\CoreRepository;
use Illuminate\Support\Facades\Validator;

class TaskLabelsRepository extends CoreRepository
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

    public function storeValidate($data)
    {
        return $this->validator($data, [
            'label_id' => 'required|array|exists:labels,id',
            'task_id' => 'required|int|exists:tasks,id'
        ]);
    }

    public function update($data, $params)
    {
        $user = $this->startConditions()->where($params);
        $result = $user->update($data);
        return $result;
    }

    public function create($data)
    {
        $result = $this->startConditions()->insert($data);
        return $result;
    }

}