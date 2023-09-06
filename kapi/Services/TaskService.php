<?php

namespace Kapi\Services;

use Exception;
use Kapi\Models\Task;
use Kapi\Models\Contact;
use Illuminate\Support\Str;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class TaskService extends ServiceBase
{
    public function __construct(
        public Task $taskModel
    ) {
    }

    /**
     * store
     *
     * @param array $data
     * @return Task
     */
    public function store(array $data): ?Task
    {
        $data["ref"] = Str::uuid();

        $task = $this->taskModel->create($this->taskModel->getFillableData($data));

        return $task;        
    }

    /**
     * update
     *
     * @param array $data
     * @param Task $user
     * @return Task|Exception
     */
    public function update(array $data, $task): ?Task
    {
        if (!$task instanceof Task) {
            throw new Exception('Second parameter should be an task object', 0);
        }

        $task->fillData($data)->save();

        return $task;       
    }

    /**
     * list
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function list(array $filters = []): LengthAwarePaginator
    {
        return $this->taskModel->paginate();
    }

    /**
     * findById
     *
     * @param integer $id
     * @return Task|null
     */
    public function findById(int $id): ?Task
    {
        return $this->taskModel->with(['user'])->findOrFail($id);
    }

    /**
     * destroyById
     *
     * @param integer $id
     * @return boolean
     */
    public function destroyById(int $id): bool
    {
        return $this->taskModel->findOrFail($id)->delete();
    }
}
