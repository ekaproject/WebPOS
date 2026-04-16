<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DistributorController extends Controller
{
    public function index()
    {
        return view('admin.distributors.index');
    }
}
