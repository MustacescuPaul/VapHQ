<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Casa;
use App\User;
use Auth;

class SertarController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:web');
    }
    public function index(Request $request)
    {

        $permisiuni_sertar = Auth::user()->users_permisiuni->sertar;
        if ($permisiuni_sertar) {
            if ($request->page) {
                $page = $request->page;
            } else {
                $page = 1;
                $sf = strtotime(Casa::on(Auth::user()->magazin)->where('tip', '=', 4)->orderBy('data', 'desc')->first()->data);
            }
            $inceput = $page * 7 + 1;
            $sfarsit = 2;
            $tip = [
                1 => 'Deschidere',
                2 => 'Depunere',
                -2 => 'Retragere',
                3 => 'Depunere banca',
                4 => 'Inchidere'
            ];
            $data_start = strtotime(Casa::on(Auth::user()->magazin)->where('tip', '=', 4)->orderBy('data', 'desc')->first()->data);

            $inc = date('Y-m-d', strtotime('-' . $inceput . 'days', $data_start));
            $sf = date('Y-m-d', strtotime('-' . $sfarsit . 'days', $data_start));
            //dd($data_start);
            $operatiuni = Casa::on(Auth::user()->magazin)->whereDate('data', '<=', $sf)->WhereDate('data', '>=', $inc)->orderBy('data', 'desc')->get();
            dd($operatiuni);

            // $temp = [];
            // $response = [];
            // $response['data'] = [];
            // $response['meta'] = [];
            // $resp = [];
            // $zile = 1;
            // $max = 9;
            // //session(['inchidere' => '']);
            // $temp['suma_deschidere'] = 0;
            // $temp['suma_inchidere'] = 0;
            // $temp['suma_retragere'] = 0;


            // foreach ($operatiuni as $op) {
            //     if ($zile < 9) {

            //         if ($op->tip == 1) {
            //             $temp['suma_deschidere'] = (float)$op->suma;
            //         }
            //         if ($op->tip == 4) {
            //             $temp['suma_inchidere'] = (float)$op->suma;
            //             $temp['vanzare'] = (float)$op->vanzari;
            //             $zile++;
            //         }
            //         if (($op->tip == 2) || ($op->tip == 3)) {
            //             $temp['suma_retragere'] += (float)$op->suma;
            //         }
            //         if ($op->tip == 4 && $temp['suma_deschidere'] >  0 && $temp['suma_inchidere'] >  0) {
            //             if ((float)$temp["suma_deschidere"] != (float)$temp['suma_inchidere']) {
            //                 // $resp['eroare'] = (float)$temp["suma_deschidere"] - (float)$temp["suma_inchidere"];
            //             } else {
            //                 $resp['eroare'] = '';
            //             }
            //         }
            //         if ($op->tip == 1 && $temp['suma_deschidere'] > 0 && $temp['suma_inchidere'] > 0) {
            //             if ((float)$temp["suma_deschidere"] + (float)$temp['vanzare'] + (float)$temp['suma_retragere'] != (float)$temp['suma_inchidere']) {
            //                 // var_dump($temp["suma_inchidere"]);
            //                 // var_dump($temp["suma_deschidere"]);
            //                 // var_dump($temp["vanzare"]);
            //                 // var_dump($temp["suma_deschidere"] + $temp['vanzare'] + $temp['suma_retragere']);
            //                 $response['data'][count($response['data']) - 1]['eroare'] =
            //                     $temp['suma_deschidere'] + $temp['vanzare'] + $temp['suma_retragere'] - $temp['suma_inchidere'];
            //             } else {
            //                 $resp['eroare'] = '';
            //             }
            //             $temp['suma_retragere'] = 0;
            //         }
            //         $resp['date'] = date("d M 'y", strtotime($op->data));
            //         $resp['ora'] = date("H:i", strtotime($op->data));;
            //         $resp['user'] = User::find($op->angajat)->prenume . ' ' . User::find($op->angajat)->nume;
            //         $resp['suma'] = $op->suma;
            //         $resp['motiv'] = $op->motiv;
            //         if ($op->suma > 0)
            //             $resp['tip'] = $tip[$op->tip];
            //         else
            //             $resp['tip'] = "Retragere";
            //         array_push($response['data'], $resp);
            //         $resp['eroare'] = '';
            //         if ($zile == 9) {
            //             session(['inchidere' => $op->id]);
            //             foreach ($response['data'] as $key => $r) {
            //                 if ($r['tip'] == 'Deschidere') {
            //                     if ($r['suma'] != $response['data'][$key + 1]['suma'])
            //                         $response['data'][$key]['eroare'] = $r['suma'] - $response['data'][$key + 1]['suma'];
            //                 }
            //             }
            //         }
            //     } else {
            //         // echo '<pre>';
            //         // print_r($response);
            //         // echo '</pre>';
            //         if (($request->paginate)) {
            //             return $response;
            //         }
            //         return view('sertar.index')->with('user', Auth::user())->with(
            //             ['response' => $response]
            //         );
            //     }
            // }
        }
    }

    public function retragereDepunere()
    {
        $retrageri = Casa::on(Auth::user()->magazin)->where('tip', '=', 2)->orWhere('tip', '=', 3)->orWhere('tip', '=', -2)->orderBy('data', 'desc')->paginate(10);
        $response = [];
        $response['data'] = [];
        $tip = [
            1 => 'Deschidere',
            2 => 'Depunere',
            3 => 'Depunere banca',
            4 => 'Inchidere'
        ];
        foreach ($retrageri as $retragere) {
            $resp['date'] = date("d M 'y", strtotime($retragere->data));
            $resp['ora'] = date("H:i", strtotime($retragere->data));
            $resp['user'] = User::find($retragere->angajat)->prenume . ' ' . User::find($retragere->angajat)->nume;
            $resp['suma'] = $retragere->suma;
            $resp['motiv'] = $retragere->motiv;
            if ($retragere->suma > 0)
                $resp['tip'] = $tip[$retragere->tip];
            else
                $resp['tip'] = "Retragere";
            array_push($response['data'], $resp);
        }
        $response['meta']['pages'] = $retrageri->lastPage();
        return $response;
    }
}
