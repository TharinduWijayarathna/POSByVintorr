<?php

namespace domain\Facades\PermissionFacade;

use domain\Services\PermissionService\PermissionService;
use Illuminate\Support\Facades\Facade;

/**
 * Permission Facade
 * php version 8
 *
 * @category Facade
 *
 * @author   Vintorr <contact@Vintorr.com>
 * @license  https://Vintorr.com Config
 *
 * @link     https://Vintorr.com
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
