<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Casa;
use App\User;

class SertarController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:web');
    }
    public function index()
    {
        $permisiuni_sertar = Auth::user()->users_permisiuni->sertar;
        if ($permisiuni_sertar) {
            $tip = [
                1 => 'Deschidere',
                2 => 'Depunere',
                -2 => 'Retragere',
                3 => 'Depunere banca',
                4 => 'Inchidere'
            ];
            $temp = [];
            $response = [];
            $zile = 0;
            $operatiuni = Casa::on(Auth::user()->magazin)->get()->sortByDesc('data');
            $ultima_operatiune = Casa::on(Auth::user()->magazin)->orderBy('data', 'desc')->first();

            if ($ultima_operatiune->tip == 4) { }

            foreach ($operatiuni as $op) {
                $temp['data'] = date("d M 'y", strtotime($op->data));
                $temp['ora'] = date("H:i", strtotime($op->data));;
                $temp['user'] = User::find($op->angajat)->prenume . ' ' . User::find($op->angajat)->nume;
                $temp['tip'] = $tip[$op->tip];
                $temp['tip'] = $tip[$op->tip];
            }
            return view('sertar.index');
        }
    }
}
