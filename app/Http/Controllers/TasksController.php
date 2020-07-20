<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\JWTHelperTrait;
use App\Repositories\TasksRepository;
use App\Repositories\BoardsRepository;
use App\Repositories\TaskLabelsRepository;

class TasksController extends Controller
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
        $this->tasksRepository = app(TasksRepository::class);
        $this->boardsRepository = app(BoardsRepository::class);
        $this->taskLabelsRepository = app(TaskLabelsRepository::class);
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

        $data = $this->tasksRepository->getValidate($request->all());

        $order_by = $data['order_by'];
        $order_type = $data['order_type'];
        $board_id = $data['board_id'];
        $label_ids = $data['label_ids'] ?? [];
        $statuses_ids = $data['statuses_ids'] ?? [];

        $board = $this->boardsRepository->find(["id" => $board_id]);

        if(!empty($user_id) && $board->owner_id == $user_id) {
            // get only user labels
            $labels_ids = \App\Models\Labels::select('id')->where('user_id', $user_id)
                                                          ->whereIn('id', $label_ids)
                                                          ->get()
                                                          ->pluck('id')
                                                          ->toArray();
            
            $task_ids = \App\Models\TaskLabels::select('task_id')->whereIn('label_id', $labels_ids)
                                                                 ->get()
                                                                 ->pluck('task_id')
                                                                 ->toArray();
            
            $task_ids = array_unique($task_ids);

            if(!empty($statuses_ids)) {
                $params['IN_STATEMENTS']["status_id"] = $statuses_ids;
            }
            
            if(!empty($task_ids)) {
                $params['IN_STATEMENTS']['id'] = $task_ids;
            }

            $params[] = ['board_id', '=', $board_id];
        } else {
            return response(['status' => 'error', 'message' => "JWT data incorect"], 400);
        }

        $result = $this->tasksRepository->paginate($params, 5, $order_by, $order_type);
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

        $user_id = $jwt_decoded['id'];

        $request_data = $request->all();

        if(!isset($request_data["status_id"]) || empty($request_data["status_id"])) {
            $request_data["status_id"] = 0;
        }

        if(!isset($request_data["image_link"]) || empty($request_data["image_link"])) {
            $request_data["image_link"] = '';
        }

        $board_owner_id = $this->boardsRepository->getUserIdByBoardId($request_data["board_id"]);

        if($board_owner_id == $user_id) {
            $data = $this->tasksRepository->storeValidate($request_data);
            $result = $this->tasksRepository->create($data);
        
            return response(["status" => "success", "result" => $result], 201);
        }

        return response(["status" => "error", "message" => "You isn't owner of this board"], 400);
    }

    /**
     * Store a newly labels relationship in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function attachLabels(Request $request, $id)
    {
        $formatted_data = [];
        
        $jwt = $request->header('Authorization') ?? '';
        $jwt_decoded = $this->jwtDecode($jwt);

        $user_id = $jwt_decoded['id'] ?? 0;

        $task = $this->tasksRepository->find(['id' => $id]);

        $request_data = $request->all();
        $data_validated = $this->taskLabelsRepository->storeValidate($request_data);

        foreach ($data_validated['label_id'] as $key => $value) {
            $formatted_data[] = [
                "label_id" => $value,
                "task_id" => (int) $data_validated['task_id']
            ];
        }

        if($task->boards->owner_id == $user_id) {
            $result = $this->taskLabelsRepository->create($formatted_data);

            return response(["status" => "success", "result" => $result], 201);
        }

        return response(["status" => "error", "message" => "You isn't owner of this task"], 400);
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
    public function destroy($id)
    {
        //
    }
}
