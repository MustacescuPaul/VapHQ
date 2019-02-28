<?php

namespace App\Http\Controllers;

use App\A_garantie;
use App\Bon;
use App\Image;
use App\Intrare;
use App\Intrare_produs;
use App\Product;
use App\Produse;
use App\Vapoint;
use Auth;
use Config;
use Illuminate\Http\Request;

class GarantiiController extends Controller
{

    public function index()
    {
        return view('garantii.index')->with('user', Auth::user());
    }

    public function deschideBonuri(Request $request)
    {
        $user = Auth::user();
        if ($request->cod_bon) {
            $bon = Bon::on($user->magazin)->where('cod', '=', $request->cod_bon)->first();
            $cart = array();
            $c = $bon->detaliu_bonuri;

            foreach ($c as $key => $product) {
                if (Produse::where('id_prod', '=', $product->id_prod)->exists()) {
                    $produs = Produse::where('id_prod', '=', $product->id_prod)->first();
                    $sn = $produs->cod;
                    if ($images = Image::where([['id_product', $product->id_prod], ['cover', '1']])->first()) {
                        $cart[$key] = ['img' => $images->id_image, 'name' => $product->nume, 'id_prod' => $product->id_prod, 'sn' => $sn];
                    } else {
                        $cart[$key] = ['img' => '', 'name' => $product->nume, 'id_prod' => $product->id_prod, 'sn' => $sn];
                    }
                }
            }
            return json_encode($cart);
        }
        if ($request->serial_number) {
            $produs = Produse::where('cod', '=', $request->serial_number)->first();
            $product = Product::on($user->magazin)->find($produs->id_prod);
            if ($images = Image::where([['id_product', $product->id_prod], ['cover', '1']])->first()) {
                $cart[0] = ['img' => $images->id_image, 'name' => $product->nume, 'id_prod' => $product->id_prod, 'sn' => $request->serial_number];
            } else {
                $cart[0] = ['img' => '', 'name' => $product->nume, 'id_prod' => $product->id_prod, 'sn' => $request->serial_number];
            }
            return json_encode($cart);
        }
        if ($request->cod_garantie) {
            $c = Produse::where('id_garantie', '=', $request->cod_garantie)->get();
            foreach ($c as $key => $prod) {

                $sn = $prod->cod;
                if ($images = Image::where([['id_product', $prod->id_prod], ['cover', '1']])->first()) {
                    $cart[$key] = ['img' => $images->id_image, 'name' => $prod->nume, 'id_prod' => $prod->id_prod, 'sn' => $sn];
                } else {
                    $cart[$key] = ['img' => '', 'name' => $prod->nume, 'id_prod' => $prod->id_prod, 'sn' => $sn];
                }
            }
        }
        return json_encode($cart);
    }

    public function intrareGarantie(Request $request)
    {
        $garantie = Produse::where([['cod', '=', $request->sn], ['id_prod', '=', $request->id_prod]])->first()->garantii;

        $intrare = new Intrare;
        $intrare->id_vapoint = $garantie->id_vapoint;
        $intrare->nume_vapoint = $garantie->nume_vapoint;
        $intrare->nume_client = $garantie->nume_client;
        $intrare->adresa_client = $garantie->adresa_client;
        $intrare->telefon_client = $garantie->telefon_client;
        $intrare->email_client = $garantie->email_client;
        $intrare->status = 'Primit @ ' . $garantie->nume_vapoint;
        $intrare->save();
    }
    public function intrareProdus(Request $request)
    {
        $garantie = Produse::where([['cod', '=', $request->sn], ['id_prod', '=', $request->id]])->first()->garantii;
        $produs = Produse::where([['cod', '=', $request->sn], ['id_prod', '=', $request->id]])->first();
        $a_gar = A_garantie::find($produs->id_prod);
        $intrare = Intrare::where('email_client', '=', $garantie->email_client)->orderBy('id', 'desc')->first();
        $intrare_produs = new Intrare_produs;
        $intrare_produs->id_service = $intrare->id;
        $intrare_produs->id_vapoint = $intrare->id_vapoint;
        $intrare_produs->id_garantie = $produs->garantii->id;
        $intrare_produs->id_vanzare = $produs->id;
        $intrare_produs->id_prod = $produs->id_prod;
        $intrare_produs->nume = $produs->nume;
        $intrare_produs->garantie = $a_gar->perioada;
        $intrare_produs->cod = $produs->cod;
        $intrare_produs->defect = $request->defect;
        $intrare_produs->stare = $request->stare;
        $intrare_produs->save();
    }

    public function garantiiIntrate()
    {
        $user = Auth::user();
        $intrari = Intrare::where('id_vapoint', '=', $user->id_vapoint)->get();

        return view('garantii.intrate')->with('user', $user)->with('intrari', $intrari);
    }

    public function produseIntrare(Request $request)
    {
        $produse = Intrare_produs::where('id_service', '=', $request->id_intrare)->get();
        return json_encode($produse);
    }

    public function primitVap(Request $request)
    {
        $user = Auth::user();
        if ($request->stat == "Expediat") {
            $vapoint = Vapoint::find($user->id_vapoint);
            $status = "Expediat @ " . $vapoint->nume;
            $intrare = Intrare::find($request->id);
            $intrare->status = $status;
            $intrare->save();
        }
        if ($request->stat == "Returnat") {
            $vapoint = Vapoint::find($user->id_vapoint);
            $intrare = Intrare::find($request->id);
            $intrare->status = 'Returnat @ ' . $vapoint->nume;
            $intrare->save();
        }
        $intrari = Intrare::where('id_vapoint', '=', $user->id_vapoint)->get();
        return json_encode($intrari);
    }

    public function primitService(Request $request)
    {
        $user = Auth::user();
        if ($request->stat == "Expediat") {
            $status = "Expediat @ Service";
            $intrare = Intrare::find($request->id);
            $intrare->status = $status;
            $intrare->save();
        }
        if ($request->stat == "Primit") {
            $intrare = Intrare::find($request->id);
            $intrare->status = 'Primit @ Service';
            $intrare->save();
        }
        $intrari = Intrare::get();
        return json_encode($intrari);
    }
    public function rezolvat(Request $request)
    {
        $request->validate([
            'text' => 'required|alpha_dash|max:255',
            'id' => 'required|numeric',
        ]);
        $intrare = Intrare::find($request->id);
        $intrare->remediat = 1;

        $produs = Intrare_produs::where('id_service', '=', $intrare->id)->first();
        $produs->remediere = $request->text;
        $produs->save();
        $intrare->save();
    }
}
