<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function __invoke(Request $request) : \Inertia\Response|\Inertia\ResponseFactory
    {
        return inertia('HomeView');
    }
}
