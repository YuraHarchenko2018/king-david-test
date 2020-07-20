<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\JWTHelperTrait;
use App\Repositories\TasksRepository;
use App\Repositories\LabelsRepository;

class LabelsController extends Controller
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
        $this->labelsRepository = app(LabelsRepository::class);
        $this->tasksRepository = app(TasksRepository::class);
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

        $data = $this->labelsRepository->getValidate($request->all());

        $order_by = $data['order_by'];
        $order_type = $data['order_type'];

        if(!empty($user_id)) {
            $params[] = ['user_id', '=', $user_id];
        } else {
            return response(['status' => 'error', 'message' => "JWT data incorect"], 400);
        }

        $result = $this->labelsRepository->paginate($params, 5, $order_by, $order_type);
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
        
        $label_data = [
            'title' => $request_data['title'] ?? '',
            'user_id' => $user_id
        ];

        $label_data_validated = $this->labelsRepository->storeValidate($label_data);
        $result = $this->labelsRepository->create($label_data_validated);

        return response(["status" => "success", "result" => $result], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function destroy($id)
    {
        //
    }
}
