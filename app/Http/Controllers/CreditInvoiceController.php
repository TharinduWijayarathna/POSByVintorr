<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CreditInvoiceController extends ParentController
{
    public function index()
    {
        return Inertia::render('CreditInvoice/index');
    }

    public function edit()
    {
        if (Auth::user()->user_role_id != User::USER_ROLE_ID['AUDIT']) {
            return Inertia::render('CreditInvoice/edit');
        }
    }
}
