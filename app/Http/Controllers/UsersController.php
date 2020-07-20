<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\JWTHelperTrait;
use App\Repositories\UsersRepository;

class UsersController extends Controller
{
    use JWTHelperTrait;

    /**
     * Create a new repository instance and set auth middleware
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['registration', 'login']]);
        $this->usersRepository = app(UsersRepository::class);
    }

    /**
     * Check user login data and return  the jwt token
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $data = $this->usersRepository->loginValidate($request->all());
        $user = $this->usersRepository->getUserByLoginData($data);

        if(!empty($user)) {
            $jwt = $this->jwtEncode($user);
    
            return response(["status" => "success", "jwt" => $jwt], 201);
        }

        return response(["status" => "error", "message" => "Can't add user"], 400);
    }

    /**
     * Check user login data and return  the jwt token
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function registration(Request $request)
    {
        $data = $this->usersRepository->registrationValidate($request->all());
        $result = $this->usersRepository->create($data);

        if(!empty($result)) {
            $jwt = $this->jwtEncode($result);
    
            return response(["status" => "success", "jwt" => $jwt], 201);
        }

        return response(["status" => "error", "message" => "Can't add user"], 400);
    }

}
