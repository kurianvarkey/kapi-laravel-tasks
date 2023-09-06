<?php

namespace Kapi\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Kapi\Models\Model;

/**
 * IServiceRepository
 */
interface IServiceRepository
{
    /**
     * store
     */
    public function store(array $data): ?Model;

    /**
     * update
     */
    public function update(array $data, $classObject): ?Model;


    /**
     * list
     */
    public function list(array $filters = []): LengthAwarePaginator;

    /**
     * find by id
     *
     * @return void
     */
    public function findById(int $id);

    /**
     * Destroy by id
     *
     * @return void
     */
    public function destroyById(int $id);
}
