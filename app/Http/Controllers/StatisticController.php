<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Dotenv\Store\File\Reader;
use App\Traits\JWTHelperTrait;
use Illuminate\Support\Facades\DB;
use App\Repositories\TasksRepository;
use App\Repositories\BoardsRepository;
use App\Repositories\TaskStatusesRepository;

class StatisticController extends Controller
{
    use JWTHelperTrait;
    
    /**
     * Create a new repository instance
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->tasksRepository = app(TasksRepository::class);
        $this->boardsRepository = app(BoardsRepository::class);
        $this->taskStatusesRepository = app(TaskStatusesRepository::class);
    }

    public function totalTask(Request $request)
    {
        $jwt = $request->header('Authorization') ?? '';
        $jwt_decoded = $this->jwtDecode($jwt);

        $user_id = $jwt_decoded['id'] ?? 0;

        $data = $this->tasksRepository->totalTaskValidate($request->all());

        $board_id = $data['board_id'];

        $board = $this->boardsRepository->find(["id" => $board_id]);

        if(!empty($user_id) && $board->owner_id == $user_id) {
            $tasks = $this->tasksRepository->findAll(['board_id' => $board_id]);
            return response(["status" => "success", "tasks" => $tasks], 201);
        }

        return response(["status" => "error", "message" => "You are not owner of this board"], 400);
    }
    
    public function totalDoneTask(Request $request)
    {
        $jwt = $request->header('Authorization') ?? '';
        $jwt_decoded = $this->jwtDecode($jwt);

        $user_id = $jwt_decoded['id'] ?? 0;

        $data = $this->tasksRepository->totalTaskValidate($request->all());

        $board_id = $data['board_id'];

        $board = $this->boardsRepository->find(["id" => $board_id]);

        $taskStatus = $this->taskStatusesRepository->find(['board_id' => $board_id, 'title' => 'done']);

        if(!empty($user_id) && $board->owner_id == $user_id) {
            $tasks = $this->tasksRepository->findAll(['board_id' => $board_id, 'status_id' => $taskStatus->id]);
            return response(["status" => "success", "tasks" => $tasks], 201);
        }

        return response(["status" => "error", "message" => "You are not owner of this board"], 400);
    }
    
    public function progressInPercentage(Request $request)
    {
        $result = [];

        $jwt = $request->header('Authorization') ?? '';
        $jwt_decoded = $this->jwtDecode($jwt);

        $user_id = $jwt_decoded['id'] ?? 0;

        $data = $this->tasksRepository->totalTaskValidate($request->all());

        $board_id = $data['board_id'];

        $board = $this->boardsRepository->find(["id" => $board_id]);

        $taskStatuses = $this->taskStatusesRepository->findAll(['board_id' => $board_id])->toArray();
        $formatedTaskStatuses = [];

        foreach ($taskStatuses as $key => $value) {
            $formatedTaskStatuses[$value['id']] = $value['title'];
            $result[$value['title']] = 0;
        }

        if(!empty($user_id) && $board->owner_id == $user_id) {
            $tasks = $this->tasksRepository->findAll(['board_id' => $board_id])->toArray();
            $oneInPercentage = 100 / count($tasks);

            foreach ($tasks as $key => $value) {
                $title = $formatedTaskStatuses[$value['status_id']];
                $result[$title] = $result[$title] + 1;
            }

            foreach ($result as $key => $value) {
                $result[$key] = $value * $oneInPercentage . "%";
            }

            return response(["status" => "success", "result" => $result], 201);
        }

        return response(["status" => "error", "message" => "You are not owner of this board"], 400);
    }
    
    public function bestUserByLastWeek(Request $request)
    {
        $max_value = 0;
        $best_user_id = 0; // =)

        $doneTitle = "done";

        $result = DB::select(sprintf('
            select 
                count(b.owner_id) as totalTask, 
                b.owner_id
            from task_statuses ts
                left join tasks t on ts.id = t.status_id
                inner join boards b on b.id = t.board_id
                where ts.title like "%s"
            GROUP BY b.owner_id
        ', $doneTitle));

        foreach ($result as $key => $value) {
            if($value->totalTask > $max_value) {
                $max_value = $value->totalTask;
                $best_user_id = $value->owner_id;
            }
        }

        return response(["status" => "success", "best_user" => $best_user_id], 200);
    }
}
