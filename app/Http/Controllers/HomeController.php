<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ps_product;
use App\Test;
use Config;
use Illuminate\Support\Facades\Auth;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
               

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
       /* Config::set("database.connections.mysql", [
            'driver' => 'mysql',
            'host' =>'127.0.0.1',
            'port' => '3306',
            'database' => 'devmarketer',
            'username' => 'root',
            'password' => '',
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
]);
        $test = new Test;

     echo($test::where('id',1)->get());
    }*/
 
    return view('home');  
    }
}
