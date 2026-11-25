<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

class AdminSystemController extends Controller
{
    public function __invoke()
    {
        return view('admin.system');
    }
}
