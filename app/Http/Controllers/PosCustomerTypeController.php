<?php

namespace App\Http\Controllers;

use App\Models\CustomerType;

/**
 * PosCustomerType Controller
 * php version 8
 *
 * @category Controller
 *
 * @author   Vintorr <contact@Vintorr.com>
 * @license  https://Vintorr.com Config
 *
 * @link     https://Vintorr.com
 * */
class PosCustomerTypeController extends ParentController
{
    /**
     * all
     * Get all customer types
     *
     * @return void
     */
    public function all()
    {
        return CustomerType::all();
    }

    /**
     * get
     * Get single customer type by slug
     *
     * @param  mixed  $slug
     * @return void
     */
    public function get(string $slug)
    {
        return CustomerType::where('slug', $slug)->first();
    }
}
