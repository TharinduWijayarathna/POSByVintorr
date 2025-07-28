<?php

namespace domain\Facades\PermissionFacade;

use domain\Services\PermissionService\PermissionService;
use Illuminate\Support\Facades\Facade;

/**
 * Permission Facade
 * php version 8
 *
 * @category Facade
 * @author   EmergentSpark <contact@emergentspark.com>
 * @license  https://emergentspark.com Config
 * @link     https://emergentspark.com
 * */
class PermissionFacade extends Facade
{
    /**
     * getFacadeAccessor
     *
     * @return void
     */
    protected static function getFacadeAccessor()
    {
        return PermissionService::class;
    }
}
