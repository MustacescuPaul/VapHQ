<?php
 //de refacut salvarea reducerii in sesiune bazata pe taburi!!
namespace App\Http\Controllers;

use App\Category;
use App\Image;
use App\Product;
use Auth;
use Config;

class RedirectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index()
    {
        $permisiuni = Auth::user()->users_permisiuni;
        $meniu_redirect = [];
        $meniu_redirect['vanzare'] = [];
        $meniu_redirect['aprovizionare'] = [];
        $meniu_redirect['garantii'] = [];
        if ($permisiuni->admin)
            $meniu_redirect['admin'] = ["Dashboard"];
        if ($permisiuni->vanzare) {
            array_push($meniu_redirect['vanzare'], "Vanzare");
        }
        if ($permisiuni->casa) {
            array_push($meniu_redirect['vanzare'], "Casa");
        }
        if ($permisiuni->stocuri) {
            array_push($meniu_redirect['vanzare'], "Stocuri");
        }
        if ($permisiuni->sertar) {
            array_push($meniu_redirect['vanzare'], "Sertar");
        }
        if ($permisiuni->mixer_baze) {
            array_push($meniu_redirect['vanzare'], "Mixer baze");
        }
        if ($permisiuni->comanda) {
            array_push($meniu_redirect['aprovizionare'], "Comenzi");
        }
        if ($permisiuni->finalizare) {
            array_push($meniu_redirect['aprovizionare'], "Comenzi in asteptare");
        }
        if ($permisiuni->istoric_comenzi) {
            array_push($meniu_redirect['aprovizionare'], "Istoric");
        }
        if ($permisiuni->stocare) {
            array_push($meniu_redirect['aprovizionare'], "Stocare");
        }
        if ($permisiuni->upload_stoc) {
            array_push($meniu_redirect['aprovizionare'], "Upload stoc");
        }
        if ($permisiuni->garantii) {
            array_push($meniu_redirect['garantii'], "Emitere");
            array_push($meniu_redirect['garantii'], "Primire service");
            array_push($meniu_redirect['garantii'], "Eliberare service");
            array_push($meniu_redirect['garantii'], "Intrari service");
        }
        return view('index')->with('user', Auth::user())->with('menu', $meniu_redirect);
    }
}
