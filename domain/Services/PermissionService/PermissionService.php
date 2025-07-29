<?php

namespace domain\Services\PermissionService;

use Spatie\Permission\Models\Permission;

/**
 * PermissionService
 * php version 8
 *
 * @category Service
 *
 * @author   Vintorr <contact@Vintorr.com>
 * @license  https://Vintorr.com Config
 *
 * @link     https://Vintorr.com
 * */
class PermissionService
{
    protected $permission;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->permission = new Permission;
    }

    /**
     * All
     * retrieve all the data from MaterialType model
     *
     * @return void
     */
    public function groups()
    {
        return $this->permission->orderBy('group_name', 'asc')->select('group_name')->distinct()->get();
    }

    public function allList()
    {
        return $this->permission->all();
    }
}
