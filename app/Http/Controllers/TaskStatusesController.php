<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\JWTHelperTrait;
use App\Repositories\BoardsRepository;
use App\Repositories\TaskStatusesRepository;

class TaskStatusesController extends Controller
{
    use JWTHelperTrait;
    
    /**
     * Create a new repository instance and set auth middleware
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->taskStatusesRepository = app(TaskStatusesRepository::class);
        $this->boardsRepository = app(BoardsRepository::class);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = [];

        $jwt = $request->header('Authorization') ?? '';
        $jwt_decoded = $this->jwtDecode($jwt);

        $user_id = $jwt_decoded['id'] ?? 0;

        $data = $this->taskStatusesRepository->getValidate($request->all());

        $order_by = $data['order_by'];
        $order_type = $data['order_type'];
        $board_id = $data['board_id'];

        $board = $this->boardsRepository->find(["id" => $board_id]);

        if(!empty($user_id) && $board->owner_id == $user_id) {
            $params[] = ['board_id', '=', $board_id];
        } else {
            return response(['status' => 'error', 'message' => "It's not your own board"], 400);
        }

        $result = $this->taskStatusesRepository->paginate($params, 5, $order_by, $order_type);
        return $result;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $jwt = $request->header('Authorization') ?? '';
        $jwt_decoded = $this->jwtDecode($jwt);

        $user_id = $jwt_decoded['id'] ?? 0;

        $request_data = $request->all();
        
        $statuses_data_validated = $this->taskStatusesRepository->storeValidate($request_data);

        $board = $this->boardsRepository->find(["id" => $request_data['board_id']]);

        if($board->owner_id == $user_id) {
            $result = $this->taskStatusesRepository->create($statuses_data_validated);

            return response(["status" => "success", "result" => $result], 201);
        }

        return response(["status" => "error", "message" => "It's not your board"], 400);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $jwt = $request->header('Authorization') ?? '';
        $jwt_decoded = $this->jwtDecode($jwt);

        $user_id = $jwt_decoded['id'] ?? 0;

        $statusRaw = $this->taskStatusesRepository->find(["id" => $id]);
        $board = $this->boardsRepository->find(["id" => $statusRaw->board_id]);

        if($board->owner_id == $user_id) {
            $result = $this->taskStatusesRepository->deleteByID($id);
            
            return response(["status" => "success", "result" => $result], 201);
        }

        return response(["status" => "error", "message" => "It's not your board-status"], 400);
    }
}
