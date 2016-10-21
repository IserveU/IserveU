<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

class HomeController extends Controller
{
    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index()
    {
        return view('index');
    }
}
