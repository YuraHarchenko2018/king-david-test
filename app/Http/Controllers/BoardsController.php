<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\JWTHelperTrait;
use App\Repositories\BoardsRepository;
use App\Repositories\TaskStatusesRepository;


class BoardsController extends Controller
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
        $this->boardsRepository = app(BoardsRepository::class);
        $this->taskStatusesRepository = app(TaskStatusesRepository::class);

        $this->defaultStatuses = [
            "backlog", 
            "development", 
            "done", 
            "review"
        ];
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

        $data = $this->boardsRepository->getValidate($request->all());

        $order_by = $data['order_by'];
        $order_type = $data['order_type'];

        if(!empty($user_id)) {
            $params[] = ['owner_id', '=', $user_id];
        } else {
            return response(['status' => 'error', 'message' => "JWT data incorect"], 400);
        }

        $result = $this->boardsRepository->paginate($params, 5, $order_by, $order_type);
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

        $request_data = $request->all();
        
        $board_data = [
            'title' => $request_data['title'] ?? '',
            'description' => $request_data['description'] ?? '',
            'owner_id' => $jwt_decoded['id'] ?? 0
        ];

        $board_data_validated = $this->boardsRepository->storeValidate($board_data);
        $result = $this->boardsRepository->create($board_data_validated);

        if(!empty($result)) {
            $statuses_data = [];

            $statuses = $request_data['statuses'] ?? $this->defaultStatuses;
            $board_id = $result->id ?? 0;

            if(!empty($board_id)) {
                foreach ($statuses as $key => $value) {
                    $statuses_data[] = [
                        'title' => $value,
                        'board_id' => $board_id
                    ];
                }

                $result = $this->taskStatusesRepository->multiple_create($statuses_data);
                
                if($result) {
                    return response(["status" => "success", "result" => $result], 201);
                }

                // if can't add stauses remove created board and return an error
                $this->boardsRepository->deleteByID($board_id);

                return response(["status" => "error", "message" => "Can't add border statuses"], 400);
            }
        }
    
        return response(["status" => "error", "message" => "Can't add this border"], 400);
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
        $jwt = $request->header('Authorization') ?? '';
        $jwt_decoded = $this->jwtDecode($jwt);

        $request_data = $request->all();

        $board_data = [
            'title' => $request_data['title'] ?? '',
            'description' => $request_data['description'] ?? ''
        ];
        
        $board_data_validated = $this->boardsRepository->updateValidate($board_data);

        // $this->authorize('update', $board);
        
        $board = $this->boardsRepository->find(['id' => $id]);
        
        if($board->owner_id == $jwt_decoded['id']) {
            $result = $this->boardsRepository->update($board_data_validated, [
                'id' => $id
            ]);
    
            return response(["status" => "success", "result" => $result], 201);
        }

        return response(["status" => "error", "message" => "It's not your board"], 201);
    }

}
