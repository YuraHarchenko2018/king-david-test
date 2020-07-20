<?php

namespace App\Repositories;

use App\Models\Users as Model;
use App\Repositories\CoreRepository;
use Illuminate\Support\Facades\Validator;

class UsersRepository extends CoreRepository
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

    public function registrationValidate($data)
    {
        return $this->validator($data, [
            'name' => 'required|string|min:3|max:50',
            'password' => 'required|string|min:6|max:100'
        ]);
    }

    public function loginValidate($data)
    {
        return $this->validator($data, [
            'name' => 'required|string|min:3|max:50',
            'password' => 'required|string|min:6|max:100'
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
        $result = $this->startConditions()->create($data);
        return $result;
    }

    public function getUserByID($id = '')
    {
        if(!empty($id)) {
            $user = $this->startConditions()->where('id', '=', $id)->first();
            
            if(!empty($user)) {
                return $user;
            }
        }

        return false;
    }
    
    public function getUserByLoginData($data)
    {
        if(!empty($data)) {
            $name = $data['name'] ?? '';
            $password = $data['password'] ?? '';

            $user = $this->startConditions()
                            ->where('name', '=', $name)
                            ->where('password', '=', $password)
                            ->first();
            
            if(!empty($user)) {
                return $user;
            }
        }

        return false;
    }

}