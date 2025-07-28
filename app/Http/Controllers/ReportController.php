<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ReportController extends ParentController
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['CASHIER']) {
            return inertia::render('Reports/index');
        }
    }
}
