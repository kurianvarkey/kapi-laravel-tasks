<?php

namespace Kapi\Http\Controllers;

use Kapi\Models\Task;
use Illuminate\Http\Response;
use Kapi\Services\TaskService;
use Kapi\Traits\AppResponseTrait;
use App\Http\Controllers\Controller;
use Kapi\Http\Requests\Task\StoreRequest;
use Kapi\Http\Requests\Task\UpdateRequest;
use Kapi\Http\Resources\Task\TaskResource;
use Kapi\Http\Resources\Task\TaskCollection;

final class TaskController extends Controller
{
    use AppResponseTrait;

    /**
     * Contructor
     */
    public function __construct(
        public TaskService $taskService
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->sendResponse(
            data: new TaskCollection($this->taskService->list())
        );
    }

    /**
     * Store a newly created task in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        return $this->sendResponse(
            data: new TaskResource($this->taskService->store($request->validated())),
            httpStatus: Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified task.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        return $this->sendResponse(
            data: new TaskResource($this->taskService->findById($id))
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @return void
     */
    public function update(Task $task, UpdateRequest $request)
    {
        return $this->sendResponse(
            data: new TaskResource($this->taskService->update($request->validated(), $task))
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $this->taskService->destroyById($id);

        return $this->sendResponse(
            data: null
        );
    }
}
