<?php

namespace App\Repositories;

use App\Models\Boards as Model;
use App\Repositories\CoreRepository;
use Illuminate\Support\Facades\Validator;

class BoardsRepository extends CoreRepository
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
            'description' => 'required|string|min:6|max:100',
            'owner_id' => 'required|int|exists:users,id'
        ]);
    }

    public function updateValidate($data)
    {
        return $this->validator($data, [
            'title' => 'string|min:3|max:50',
            'description' => 'string|min:6|max:100'
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

    public function find($params)
    {
        $board = $this->startConditions()->where($params)->first();
        return $board;
    }

    public function update($data, $params)
    {
        $board = $this->startConditions()->where($params);
        $result = $board->update($data);
        return $result;
    }

    public function create($data)
    {
        $result = $this->startConditions()->create($data);
        return $result;
    }

    public function getUserIdByBoardId($board_id)
    {
        $user = $this->startConditions()->where("id", "=", $board_id)->first();
        return $user->owner_id ?? 0;
    }

    public function deleteByID($id) {
        $result = $this->startConditions()->findOrFail($id)->delete();
        return $result;
    }

}