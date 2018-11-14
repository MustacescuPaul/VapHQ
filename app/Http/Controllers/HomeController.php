<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ps_product;
use App\Test;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $test = new Test;
        //$test->setConnection('mysql2');
        echo($test->find(1));

        return view('home');
    }
}
