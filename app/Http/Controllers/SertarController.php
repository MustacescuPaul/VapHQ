<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Casa;
use App\User;
use Auth;
use Session;
use Response;

class SertarController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:web');
    }
    public function index(Request $request)
    {
        //$request->session()->flush();


        $permisiuni_sertar = Auth::user()->users_permisiuni->sertar;
        if ($permisiuni_sertar) {
            $temp = 1;
            $saptamana = [];
            $operatii = [];
            $zi = [];
            $zi['motiv_r'] = '';
            $zi['motiv'] = '';
            $zi['motiv_d'] = '';
            $zi['motiv_db'] = '';
            //session(['saptamana' => []]);

            if ($request->page) {
                $page = $request->page;
            } else {
                $page = 1;
            }
            if ($page > 1) {
                $sapt = session('saptamana');
                $operatiuni = Casa::on(Auth::user()->magazin)->orderBy('data', 'desc')->where('id', '<=', $sapt[$page - 1])->get();
            } else
                $operatiuni = Casa::on(Auth::user()->magazin)->orderBy('data', 'desc')->get();
            //dd($operatiuni[0]);
            $zi['depunere'] = 0;
            $zi['retragere'] = 0;
            $zi['depunere_banca'] = 0;
            foreach ($operatiuni as $key => $op) {
                if ($temp < 8) {
                    if ($op->tip == 4) {
                        $zi['inchidere'] = $op->suma;
                        $zi['vanzari'] = $op->vanzari;
                        $zi['date_i'] = date("d M 'y", strtotime($op->data));
                        $zi['ora_i'] = date("H:i", strtotime($op->data));
                        if ($temp == 7) {
                            $sapt = session('saptamana');
                            $sapt[$page] = $op->id;
                            //dd($sapt);
                            session(
                                ['saptamana' =>
                                $sapt]
                            );
                        }
                    }
                    if ($op->tip == 2) {

                        if ($op->suma > 0) {
                            if ($zi['motiv_d'])
                                $zi['motiv_d'] .= '+' . $op->motiv;
                            else
                                $zi['motiv_d'] .=  $op->motiv;
                            $zi['depunere'] += $op->suma;
                            array_push($operatii, ['motiv' => $op->motiv, 'depunere' => $op->suma]);
                        } else {
                            if ($zi['motiv_r'])
                                $zi['motiv_r'] .= '+' . $op->motiv;
                            else
                                $zi['motiv_r'] .=  $op->motiv;
                            $zi['retragere'] += $op->suma;
                            array_push($operatii, ['motiv' => $op->motiv, 'retragere' => $op->suma]);
                        }
                    }
                    if ($op->tip == 3) {
                        if ($zi['motiv_db'])
                            $zi['motiv_db'] .= '+' . $op->motiv;
                        else
                            $zi['motiv_db'] .=  $op->motiv;
                        $zi['depunere_banca'] += $op->suma;
                        array_push($operatii, ['motiv' => $op->motiv, 'depunere_banca' => $op->suma]);
                    }
                    if ($op->tip == 1) {
                        $zi['deschidere'] = $op->suma;
                        $zi['operatii'] = $operatii;
                        $operatii = [];
                        $zi['date'] = date("d M 'y", strtotime($op->data));
                        $zi['ora'] = date("H:i", strtotime($op->data));
                        $zi['user'] = User::find($op->angajat)->prenume . ' ' . User::find($op->angajat)->nume;



                        if ((float)$zi['deschidere'] + (float)$zi['retragere'] + (float)$zi['vanzari'] != (float)$zi['inchidere']) {
                            $zi['eroare_inchidere'] = (float)$zi['deschidere'] + (float)$zi['retragere'] + (float)$zi['vanzari'] -  (float)$zi['inchidere'];
                        }
                        array_push($saptamana, $zi);

                        $temp++;
                        $zi['eroare_inchidere'] = 0;
                        $zi['inchidere'] = 0;
                        $zi['deschidere'] = 0;
                        $zi['depunere'] = 0;
                        $zi['retragere'] = 0;
                        $zi['depunere_banca'] = 0;
                        $zi['motiv_r'] = '';
                        $zi['motiv'] = '';
                        $zi['motiv_d'] = '';
                        $zi['motiv_db'] = '';
                    }
                } else {

                    //dd(session('saptamana'));
                    foreach ($saptamana as $key => $zi) {
                        if ($key < 6)
                            if ($zi['deschidere'] != $saptamana[$key + 1]['inchidere'])                             $saptamana[$key]['eroare_deschidere'] = $saptamana[$key + 1]['inchidere'] - $zi['deschidere'];
                    }
                    //var_dump(session('saptamana'));
                    //return Response::json($saptamana);
                    return view('sertar.index')->with('user', Auth::user())->with(
                        ['response' => $saptamana]
                    );
                }
            }


            // if ($request->page) {
            //     $page = $request->page;
            // } else {
            //     $page = 1;
            //     $sf = strtotime(Casa::on(Auth::user()->magazin)->where('tip', '=', 4)->orderBy('data', 'desc')->first()->data);
            // }
            // $inceput = $page * 7 + 1;
            // $sfarsit = 2;
            // $tip = [
            //     1 => 'Deschidere',
            //     2 => 'Depunere',
            //     -2 => 'Retragere',
            //     3 => 'Depunere banca',
            //     4 => 'Inchidere'
            // ];
            // $data_start = strtotime(Casa::on(Auth::user()->magazin)->where('tip', '=', 4)->orderBy('data', 'desc')->first()->data);

            // $inc = date('Y-m-d', strtotime('-' . $inceput . 'days', $data_start));
            // $sf = date('Y-m-d', strtotime('-' . $sfarsit . 'days', $data_start));
            // //dd($data_start);
            // $operatiuni = Casa::on(Auth::user()->magazin)->whereDate('data', '<=', $sf)->WhereDate('data', '>=', $inc)->orderBy('data', 'desc')->get();
            // dd($operatiuni);

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
