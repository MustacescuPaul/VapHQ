<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Angajati;
use Config;
class GalatiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
         $this->middleware('auth:web');


    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dm = app("Illuminate\\Database\\DatabaseManager");

$dm->disconnect();
         Config::set("database.connections.mysql", [
            'driver' => 'mysql',
            'host' =>'127.0.0.1',
            'port' => '3306',
            'database' => 'galati',
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
      $test = new Angajati;
          echo($test::where('id_user',1)->get());
    //return view('admin');    
    }
}
