<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskListRequest;
use App\Http\Requests\UpdateTaskListRequest;
use App\Http\Resources\TaskListResource;
use App\TaskList;
use Illuminate\Http\Request;

class TaskListController extends Controller
{
    public function today(Request $request)
    {
        return response()->json(TaskListResource::collection(auth()->user()->taskList()->whereDate('created_at', today())->get()));
    }

    public function all(Request $request)
    {
        return response()->json(TaskListResource::collection(auth()->user()->taskList));
    }

    public function store(CreateTaskListRequest $request)
    {
        if (auth()->user()->taskList()->whereDate('created_at', today())->count() > 4) {
            return response()->json(['message' => 'Task limit exceeded!'], 400);
        }
        $validated = $request->validated();
        $taskList = new TaskList($validated);
        auth()->user()->taskList()->save($taskList);
        return response()->json(new TaskListResource($taskList));
    }

    public function update(UpdateTaskListRequest $request, $id)
    {
        $validated = $request->validated();

        $task = auth()->user()->taskList()->whereDate('created_at', today())->whereId($id)->first();

        if (!$task) {
            return response()->json(['message' => 'Not found!'], 404);
        }

        $task->update($validated);

        return response()->json(new TaskListResource($task));
    }
}
