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
                //selecteaza cate 7 zile
                if ($temp < 8) {
                    //inchidere
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
                    //depunere/retragere
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
                    //depunere banca
                    if ($op->tip == 3) {
                        if ($zi['motiv_db'])
                            $zi['motiv_db'] .= '+' . $op->motiv;
                        else
                            $zi['motiv_db'] .=  $op->motiv;
                        $zi['depunere_banca'] += $op->suma;
                        array_push($operatii, ['motiv' => $op->motiv, 'depunere_banca' => $op->suma]);
                    }
                    //deschidere
                    if ($op->tip == 1) {
                        $zi['deschidere'] = $op->suma;
                        $zi['operatii'] = $operatii;
                        $operatii = [];
                        $zi['date'] = date("d M 'y", strtotime($op->data));
                        $zi['ora'] = date("H:i", strtotime($op->data));
                        $zi['user'] = User::find($op->angajat)->prenume . ' ' . User::find($op->angajat)->nume;


                        //verificare erari la inchidere
                        if ((float)$zi['deschidere'] + (float)$zi['retragere'] + (float)$zi['vanzari'] != (float)$zi['inchidere']) {
                            $zi['eroare_inchidere'] = (float)$zi['deschidere'] + (float)$zi['retragere'] + (float)$zi['vanzari'] -  (float)$zi['inchidere'];
                        }
                        //salveaza ziua
                        array_push($saptamana, $zi);

                        //pregatire pentru urmattoarea iteratie
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

                    //nu verifica prima deschidere din db
                    foreach ($saptamana as $key => $zi) {
                        if ($key < 6)
                            if ($zi['deschidere'] != $saptamana[$key + 1]['inchidere'])                             $saptamana[$key]['eroare_deschidere'] = $saptamana[$key + 1]['inchidere'] - $zi['deschidere'];
                    }
                    //return pentru requesturile din Vue
                    if ($request->paginate)
                        return $saptamana;
                    //return pentru requesturile GET normale
                    return view('sertar.index')->with('user', Auth::user())->with(
                        ['response' => $saptamana]
                    );
                }
            }
            //pentru ultimele inregistrari din db(in cazul in care numarul total de zile din db nu se imparte fix la 7) intra mai jos
            foreach ($saptamana as $key => $zi) {
                if ($key < 4)
                    if ($zi['deschidere'] != $saptamana[$key + 1]['inchidere'])                             $saptamana[$key]['eroare_deschidere'] = $saptamana[$key + 1]['inchidere'] - $zi['deschidere'];
            }

            if ($request->paginate)
                return $saptamana;
            return view('sertar.index')->with('user', Auth::user())->with(
                ['response' => $saptamana]
            );
        }
    }

    public function retragereDepunere()
    {
        $permisiuni_sertar = Auth::user()->users_permisiuni->sertar;
        $response = [];
        if ($permisiuni_sertar) {
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
        }
        return $response;
    }
    public function depunereBanca()
    {
        $permisiuni_sertar = Auth::user()->users_permisiuni->sertar;
        $permisiuni_boss = Auth::user()->users_permisiuni->boss;
        $response = [];
        if ($permisiuni_sertar && $permisiuni_boss) {
            $depuneri_banca = Casa::on(Auth::user()->magazin)->where('tip', '=', 3)->orderBy('data', 'desc')->paginate(10);
            $response = [];
            $response['data'] = [];
            $tip = [
                1 => 'Deschidere',
                2 => 'Depunere',
                3 => 'Depunere banca',
                4 => 'Inchidere'
            ];
            foreach ($depuneri_banca as $depunere) {
                $resp['date'] = date("d M 'y", strtotime($depunere->data));
                $resp['ora'] = date("H:i", strtotime($depunere->data));
                $resp['user'] = User::find($depunere->angajat)->prenume . ' ' . User::find($depunere->angajat)->nume;
                $resp['suma'] = $depunere->suma;
                $resp['id'] = $depunere->id;
                $resp['motiv'] = $depunere->motiv;
                $resp['verificat'] = $depunere->verificat;
                if ($depunere->suma > 0)
                    $resp['tip'] = $tip[$depunere->tip];
                else
                    $resp['tip'] = "Retragere";
                array_push($response['data'], $resp);
            }
            $response['meta']['pages'] = $depuneri_banca->lastPage();
        }
        return $response;
    }

    public function valideazaDepunere(Request $request)
    {
        $permisiuni_sertar = Auth::user()->users_permisiuni->sertar;
        $permisiuni_boss = Auth::user()->users_permisiuni->boss;

        if ($permisiuni_sertar && $permisiuni_boss) {
            $depunere_banca = Casa::on(Auth::user()->magazin)->find($request->id);
            if ($depunere_banca->verificat == 0)
                $depunere_banca->verificat = 1;
            else
                $depunere_banca->verificat = 0;
            $depunere_banca->save();

            $this->depunereBanca();
        }
    }
}
