<?php

namespace App\Http\Controllers;

class ParentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified']);
    }
}
