<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\JWTHelperTrait;
use App\Repositories\UsersRepository;

class Authenticate
{
    use JWTHelperTrait;

    /**
     * Handle an incoming request.
     * Created a Auth
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $jwt = $request->header('Authorization') ?? '';

        if(!empty($jwt)) {
            $jwt_decoded = $this->jwtDecode($jwt);

            if($jwt_decoded) {
                $user_id = $jwt_decoded['id'];
                $userRepo = new UsersRepository();
                $user = $userRepo->getUserByID($user_id);
        
                if(!empty($user)) {
                    return $next($request);
                } else {
                    $message = json_encode($user); // "Not Authorized";
                }
            } else {
                $message = "Failed decode a JWT toket";
            }
        } else {
            $message = "Empty JWT token or not exist";
        }

        return response(['status' => 'error', 'message' => $message, 'jwt' => $jwt], 400);
    }
}
