<?php

namespace Kapi\Services;

use Exception;
use Illuminate\Support\Facades\DB;

/**
 * ServiceBase class
 */
abstract class ServiceBase implements IServiceRepository
{
    /**
     * No of tries when deadlock occurs
     *
     * @var integer
     */
    private $num_db_tries = 2;

    /**
     * Calling db transactions with the number of tries
     *
     * @param [type] $callback
     * @return void
     */
    protected function doDbTransaction($callback)
    {
        try {
            return DB::transaction($callback, $this->num_db_tries);
        } catch (Exception $e) {
            throw $e;
        }
    }    
}
