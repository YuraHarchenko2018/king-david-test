<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\TaskImageAfterLoadJob;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        $uid = $request->uid;
        $image_name = 'image.png';

        if($request->hasFile('image')) {
            $request->file('image');
            $request->image->storeAs('image', $uid . '/' . $image_name);

            TaskImageAfterLoadJob::dispatch($image_name, $uid);

            return response(["status" => "success"], 201);
        }

        return response(["status" => "error"], 400);
    }
}
