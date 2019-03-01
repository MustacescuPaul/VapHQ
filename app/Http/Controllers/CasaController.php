<?php
 //de refacut salvarea reducerii in sesiune bazata pe taburi!!
namespace App\Http\Controllers;

use App\A_garantie;
use App\Bon;
use App\Category;
use App\Detaliu_bonuri;
use App\Garantii;
use App\Image;
use App\Product;
use App\Produse;
use App\TagId;
use App\Vapoint;
use Auth;
use Config;
use Illuminate\Http\Request;
use function GuzzleHttp\json_decode;

class CasaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    // public function getParentCats($id)
    // {
    //     $parinti = array();
    //     $elem = array();
    //     $parents = Category::on('vapez')->where('active', 1)->get(['id_parent'])->sortBy('position');

    //     foreach ($parents as $value) {
    //         var_dump($value->position);
    //         array_push($parinti, $value->id_parent);
    //     }
    //     foreach ($parinti as $key => $value) {
    //         $categ = Category::on('vapez')->where('id_parent', $value)->get()->sortBy('position');
    //         $elem[$value] = array();
    //         foreach ($categ as $cat) {
    //             $elem[$value][$cat->id_category] = $cat->CategoryLang()->first()->name;
    //         }
    //     }
    //     return $elem;
    // }
    public function getParentCats($id)
    {
        $parinti = array();
        $elem = array();
        $categ = Category::on('vapez')->where([['active', 1], ['id_parent', $id]])->get()->sortBy('position');

        foreach ($categ as $cat) {
            $elem[$cat->position] = [$cat->id_category, $cat->CategoryLang()->first()->name];
        }

        return $elem;
    }

    public function index()
    {

        $tab = session('tab');
        if (!$tab) {
            session(['tab' => 'Tab1']);
        }

        $this->show();
        return view('casa.index')->with('user', Auth::user());
    }

    public function show()
    {
        $this->aplicare_reduceri();
        $user = Auth::user();
        $this->reducere_tag();
        $tab = 'App\\' . session('tab');
        $cart = $tab::on($user->magazin)->where([['id_prod', '!=', 99998], ['id_prod', '!=', 99999], ['id_prod', '!=', 99995], ['id_prod', '!=', 99996], ['id_prod', '!=', 99997], ['id_prod', '!=', 99994], ['id_prod', '!=', 99993], ['id_prod', '!=', 99992], ['id_prod', '!=', 99991], ['id_prod', '!=', 99990]])->get();

        if ($cart->isEmpty()) {
            $tab::on($user->magazin)->truncate();
        }

        foreach ($cart as $key => $product) {
            $images = Image::where([['id_product', $product->id_prod], ['cover', '1']])->get(['id_image']);
            foreach ($images as $image) {
                $cart[$key]['img'] = $image->id_image;
            }
        }
        $reduceri = $tab::on($user->magazin)->where([['id_prod', '<=', 99999], ['id_prod', '>=', 99990]])->get();
        $array = array();
        $array['produse'] = $cart;
        foreach ($reduceri as $value) {
            $array['reduceri'][$value->id_prod] = $value;
        }
        if (!$reduceri) {
            $array['reduceri'] = array();
        }
        return json_encode($array);
    }

    public function sidebar(Request $request)
    {
        if ($request->id) {

            $items = $this->getParentCats($request->id);



            return $items;
        } else {
            $items = array();
            $items = json_encode($items);

            return $items;
        }
    }

    public function productList($id)
    {

        $user = Auth::user();
        $categ = Category::on($user->magazin)->find($id);
        $products_json = array();
        $products_json['products'] = $categ->products;

        foreach ($categ->products as $product) {
            $images = Image::where('id_product', $product->id_prod)->get(['id_image']);
            $products_json['images'][$product->id_prod] = array();
            foreach ($images as $image) {
                array_push($products_json['images'][$product->id_prod], $image->id_image);
            }
        }

        return json_encode($products_json);
    }

    public function get_tab()
    {

        $tab = session('tab');
        return json_encode($tab);
        $user = Auth::user();
    }

    public function set_tab($tab)
    {
        if (($tab == 'Tab1') || ($tab == 'Tab2') || ($tab == 'Tab3') || ($tab == 'Tab4') || ($tab == 'Tab5') || ($tab == 'Tab6')) {
            session(['tab' => $tab]);
        }

        return redirect()->route('casa.index');
    }

    public function addToCart($id_prod)
    {

        $user = Auth::user();

        $prod = Product::on($user->magazin)->find($id_prod);

        $tab = 'App\\' . session('tab');
        if (!$tab::on($user->magazin)->find($id_prod)) {
            $cart = new $tab;
            $cart->setConnection($user->magazin);
            $cart->id_prod = $prod->id_prod;
            $cart->pret = $prod->pret;
            $cart->nume = $prod->nume;
            $cart->cantitate = '1';
            $cart->intrare = $prod->intrare;
            $cart->sn = $this->checkWarranty($id_prod);
            $cart->save();
        } elseif ($tab::on($user->magazin)->find($id_prod)) {
            $prod = $tab::on($user->magazin)->find($id_prod);
            $prod->cantitate += 1;
            $prod->save();
        }

        //$this->index();
    }

    public function increaseQ($id_prod)
    {

        $user = Auth::user();
        $prod = Product::on($user->magazin)->find($id_prod);

        $tab = 'App\\' . session('tab');
        $cart = $tab::on($user->magazin)->find($id_prod);
        $cart->cantitate += '1';
        $cart->save();

        return json_encode($cart->cantitate);
        // $this->index();
    }

    public function decreaseQ($id_prod)
    {

        $user = Auth::user();

        $prod = Product::on($user->magazin)->find($id_prod);

        $tab = 'App\\' . session('tab');
        $cart = $tab::on($user->magazin)->find($id_prod);
        if ($cart->cantitate - 1 < 1) {
            $cart->delete();
            $cart = $tab::on($user->magazin)->where([['id_prod', '!=', 99998], ['id_prod', '!=', 99999], ['id_prod', '!=', 99995], ['id_prod', '!=', 99996], ['id_prod', '!=', 99997], ['id_prod', '!=', 99994], ['id_prod', '!=', 99993], ['id_prod', '!=', 99992], ['id_prod', '!=', 99991], ['id_prod', '!=', 99990]])->get();
            if ($cart->isEmpty()) {
                $tab::on($user->magazin)->truncate();
            }
            return json_encode('0');
        } else {
            $cart->cantitate -= '1';
            $cart->save();
        }
        $c = $tab::on($user->magazin)->where([['id_prod', '!=', 99998], ['id_prod', '!=', 99999], ['id_prod', '!=', 99995], ['id_prod', '!=', 99996], ['id_prod', '!=', 99997], ['id_prod', '!=', 99994], ['id_prod', '!=', 99993], ['id_prod', '!=', 99992], ['id_prod', '!=', 99991], ['id_prod', '!=', 99990]])->get();
        if ($c->isEmpty()) {
            $tab::on($user->magazin)->truncate();
        }

        return json_encode($cart->cantitate);
    }

    public function checkWarranty($id_prod)
    {
        $garantie = A_garantie::find($id_prod);
        if ($garantie) {
            return $garantie->garantie;
        } else {
            return 0;
        }
    }
    public function saveSerial(Request $request)
    {
        $request->validate([
            'id_prod' => 'required|numeric',
            'serial' => 'required|alpha_dash',
        ]);
        $user = Auth::user();
        $tab = 'App\\' . session('tab');
        $prod = $tab::on($user->magazin)->find($request->id_prod);

        $prod->sn = $request->serial;
        $prod->save();
    }
    public function search($name)
    {
        $user = Auth::user();
        $products = Product::on($user->magazin)->where('nume', $name)->orWhere('nume', 'like', '%' . $name . '%')->get();
        return json_encode($products);
    }

    public function incasare(Request $request)
    {
        $user = Auth::user();
        if ($request->cugarantie == 1) {
            $request->validate([
                'email' => 'required|email',
                'nume' => 'required|max:50|regex:/^[\pL\s]+$/u',
                'adresa' => 'required|max:100|regex:/^[\pL\s]+$/u',
                'telefon' => 'required|numeric',

            ]);

            $vapoint = Vapoint::find($user->id_vapoint);

            $garantie = new Garantii;
            $garantie->id_vapoint = $vapoint->id;
            $garantie->nume_vapoint = $vapoint->nume;
            $garantie->nume_client = $request->nume;
            $garantie->adresa_client = $request->adresa;
            $garantie->telefon_client = $request->telefon;
            $garantie->email_client = $request->email;
            $garantie->save();

            $tab = 'App\\' . session('tab');
            $cart = $tab::on($user->magazin)->where('sn', '>', 0)->get();

            foreach ($cart as $key => $product) {
                $produs = new Produse;
                $garantie = Garantii::where('email_client', $request->email)->orderBy('data', 'desc')->first();
                $produs->garantie = A_garantie::find($product->id_prod)->perioada;
                $produs->id_prod = $product->id_prod;
                $produs->nume = $product->nume;
                $produs->nume_vapoint = $vapoint->nume;
                $produs->cod = $product->sn;
                $produs->id_vapoint = $user->id_vapoint;
                $produs->id_garantie = $garantie->id;
                $produs->save();
            }
        }
        //99999 id tag
        if ($request->id_tag > 0) {
            $tab = 'App\\' . session('tab');
            if (!$tab::on($user->magazin)->find(99999)) {
                $produs_reducere = new $tab;
                $produs_reducere->setConnection($user->magazin);
                $produs_reducere->pret = 0;
                $produs_reducere->id_prod = 99999;
                $produs_reducere->nume = $request->id_tag;
                $produs_reducere->cantitate = 1;
                $produs_reducere->intrare = 0;
                $produs_reducere->save();

                $tag = TagId::find($request->id_tag);
                $total = 0;
                $tot = $tab::on($user->magazin)->where([['id_prod', '!=', 99998], ['id_prod', '!=', 99999], ['id_prod', '!=', 99995], ['id_prod', '!=', 99996], ['id_prod', '!=', 99997], ['id_prod', '!=', 99994], ['id_prod', '!=', 99993], ['id_prod', '!=', 99992], ['id_prod', '!=', 99991], ['id_prod', '!=', 99990]])->get();
                foreach ($tot as $value) {
                    $total = $total + ($value->pret * $value->cantitate);
                }

                $produs_reducere = new $tab;
                $produs_reducere->setConnection($user->magazin);
                $produs_reducere->pret = ($tag->reducere / 100) * $total;
                $produs_reducere->id_prod = 99998;
                $produs_reducere->nume = 'Reducere fideliTAG';
                $produs_reducere->cantitate = 1;
                $produs_reducere->intrare = 0;
                $produs_reducere->save();
            } elseif ($produs_reducere = $tab::on($user->magazin)->find(99999)) {

                $produs_reducere->pret = 0;
                $produs_reducere->id_prod = 99999;
                $produs_reducere->nume = $request->id_tag;
                $produs_reducere->cantitate = 1;
                $produs_reducere->intrare = 0;
                $produs_reducere->save();

                $tag = TagId::find($request->id_tag);
                $total = 0;
                $tot = $tab::on($user->magazin)->where([['id_prod', '!=', 99998], ['id_prod', '!=', 99999], ['id_prod', '!=', 99995], ['id_prod', '!=', 99996], ['id_prod', '!=', 99997], ['id_prod', '!=', 99994], ['id_prod', '!=', 99993], ['id_prod', '!=', 99992], ['id_prod', '!=', 99991], ['id_prod', '!=', 99990]])->get();
                foreach ($tot as $value) {
                    $total = $total + ($value->pret * $value->cantitate);
                }

                $produs_reducere = $tab::on($user->magazin)->find(99998);

                $produs_reducere->pret = ($tag->reducere / 100) * $total;

                $produs_reducere->id_prod = 99998;
                $produs_reducere->nume = 'Reducere fideliTAG';
                $produs_reducere->cantitate = 1;
                $produs_reducere->intrare = 0;
                $produs_reducere->save();
            }
        }
        if (isset($request->reducere) > 0) {
            $tab = 'App\\' . session('tab');
            $temp = json_decode($this->aplicare_reduceri());
            $total_dupa_reduceri = $temp->total;
            if (($total_dupa_reduceri - $request->reducere) > 0) {
                if (!$tab::on($user->magazin)->find(99995)) {
                    $produs_reducere = new $tab;
                    $produs_reducere->setConnection($user->magazin);
                    $produs_reducere->pret = -$request->reducere;
                    $produs_reducere->id_prod = 99995;
                    $produs_reducere->nume = 'reducere';
                    $produs_reducere->cantitate = 1;
                    $produs_reducere->intrare = 0;
                    $produs_reducere->save();
                } else {
                    $produs_reducere = $tab::on($user->magazin)->find(99995);
                    $produs_reducere->pret = -$request->reducere;
                    $produs_reducere->id_prod = 99995;
                    $produs_reducere->nume = 'reducere';
                    $produs_reducere->cantitate = 1;
                    $produs_reducere->intrare = 0;
                    $produs_reducere->save();
                }
            }
        }

        /*if ($request->reducere == 0) {
			$tab = 'App\\' . session('tab');
			if (!$tab::on($user->magazin)->find(0)) {
				$produs_reducere = new $tab;
				$produs_reducere->setConnection($user->magazin);
				$produs_reducere->pret = 0;
				$produs_reducere->id_prod = 0;
				$produs_reducere->nume = 'reducere';
				$produs_reducere->cantitate = 1;
				$produs_reducere->intrare = 0;
				$produs_reducere->save();
			}
		}*/

        if ($request->metoda) {
            $tab = 'App\\' . session('tab');
            $cart = $tab::on($user->magazin)->where([['id_prod', '!=', 99998], ['id_prod', '!=', 99999], ['id_prod', '!=', 99995], ['id_prod', '!=', 99996], ['id_prod', '!=', 99997], ['id_prod', '!=', 99994], ['id_prod', '!=', 99993], ['id_prod', '!=', 99992], ['id_prod', '!=', 99991], ['id_prod', '!=', 99990]])->get();
            $tag = $tab::on($user->magazin)->find(99999);
            $bon = new Bon;
            $bon->setConnection($user->magazin);
            $temp = $this->aplicare_reduceri();
            $reduceri = json_decode($temp);

            $bon->id_user = $user->id;
            $bon->intrare = $cart->sum('intrare');
            $bon->platit = $reduceri->total;
            if ($request->metoda == 'cash') {
                $bon->cash = $reduceri->total;
                $bon->card = 0;
            }
            if ($request->metoda == 'card') {
                $bon->card = $reduceri->total;
                $bon->cash = 0;
            }
            if ($tag) {
                $bon->tag = $tag->nume;
            }
            $reduceri = $tab::on($user->magazin)->where([['id_prod', '<=', 99999], ['id_prod', '>=', 99990]])->sum('pret');
            $bon->reducere = $reduceri;
            $bon->refacut = 0;
            $bon->cod = rand(10000000, 99999999);
            $bon->livrat = 0;
            $bon->anulata = 0;

            $bon->save();
            $tab::on($user->magazin)->truncate();

            $bon = Bon::on($user->magazin)->where('id_user', $user->id)->orderBy('id_bon', 'desc')->first();

            foreach ($cart as $product) {
                $detaliu = new Detaliu_bonuri;
                $detaliu->setConnection($user->magazin);
                $detaliu->id_bon = $bon->id_bon;
                $detaliu->id_prod = $product->id_prod;
                $detaliu->nume = $product->nume;
                $detaliu->cantitate = $product->cantitate;
                $detaliu->returnat = 0;
                $detaliu->pret = $product->pret;
                $detaliu->intrare = $product->intrare;
                $detaliu->save();
            }
        }
    }

    public function aplicare_reduceri()
    {
        $user = Auth::user();
        $tab = 'App\\' . session('tab');
        $produse = $tab::on($user->magazin)->where([['id_prod', '!=', 99998], ['id_prod', '!=', 99999], ['id_prod', '!=', 99995], ['id_prod', '!=', 99996], ['id_prod', '!=', 99997], ['id_prod', '!=', 99994], ['id_prod', '!=', 99993], ['id_prod', '!=', 99992], ['id_prod', '!=', 99991], ['id_prod', '!=', 99990]])->get();

        $reducere_magazin = $tab::on($user->magazin)->find(99995);
        $pret_total = 0;
        $total_reduceri = array();

        foreach ($produse as $produs) {
            $pret_total = $pret_total + $produs->pret * $produs->cantitate;
        }
        if ($reducere_magazin) {
            $total_reduceri['reducere_magazin'] = $reducere_magazin->pret;
            $pret_total = $pret_total + $reducere_magazin->pret;
        }

        $this->reducere_tag();
        $reducere_tag = $tab::on($user->magazin)->find(99998);
        if ($reducere_tag) {
            $total_reduceri['reducere_tag'] = $reducere_tag->pret;
            $pret_total = $pret_total + $reducere_tag->pret;
        }
        $reducere = $this->reducere_tot($pret_total);
        if ($reducere > 0) {
            $total_reduceri['reducere_adaos'] = $reducere;
            $pret_total = $pret_total + $reducere;
        }
        if ($reducere < 0) {
            $total_reduceri['reducere_discount'] = $reducere;
            $pret_total = $pret_total + $reducere;
        }

        $total_reduceri['total'] = $pret_total;

        return json_encode($total_reduceri);
    }

    public function reducere_tag()
    {
        $user = Auth::user();
        $tab = 'App\\' . session('tab');
        $tag = $tab::on($user->magazin)->find(99999);
        if ($tag) {
            $tag = TagId::find($tag->nume);
            $total = 0;
            $tot = $tab::on($user->magazin)->where([['id_prod', '!=', 99998], ['id_prod', '!=', 99999], ['id_prod', '!=', 99996], ['id_prod', '!=', 99997], ['id_prod', '!=', 99994], ['id_prod', '!=', 99993], ['id_prod', '!=', 99992], ['id_prod', '!=', 99991], ['id_prod', '!=', 99990]])->get();
            foreach ($tot as $value) {
                $total = $total + $value->pret * $value->cantitate;
            }
            $produs_reducere = $tab::on($user->magazin)->find(99998);

            $produs_reducere->pret = -($tag->reducere / 100) * $total;
            $produs_reducere->id_prod = 99998;
            $produs_reducere->nume = ' Reducere fideliTA G';
            $produs_reducere->cantitate = 1;
            $produs_reducere->intrare = 0;
            $produs_reducere->save();
        }
    }


    public function reducere_tot($pret)
    {
        $user = Auth::user();
        $sum_pret = (double)$pret;
        $intreg = (int)$sum_pret;
        $dec = $sum_pret - $intreg;
        $adaos = 0;
        $discount = 0;
        if ($sum_pret > 10) {
            if ($dec == 0) {
                return 0;
            }

            if ($dec == 0.5) {
                return 0;
            }

            if ($dec < 0.25) {
                $discount = $dec;
            }

            if ($dec >= 0.25 and $dec < 0.5) {
                $adaos = 0.5 - $dec;
            }

            if ($dec > 0.5 and $dec < 0.75) {
                $discount = $dec - 0.5;
            }

            if ($dec >= 0.75) {
                $adaos = 1 - $dec;
            }
        } else {
            if ($dec == 0) {
                return 0;
            }

            if ($dec == 0.5) {
                return 0;
            }

            if ($dec < 0.5) {
                $adaos = 0.5 - $dec;
            }

            if ($dec > 0.5) {
                $adaos = 1 - $dec;
            }
        }
        $tab =  'App\\' . session('tab');
        if ($adaos > 0) {

            if (!$tab::on($user->magazin)->find(99996)) {
                $produs_reducere = new $tab;
                $produs_reducere->setConnection($user->magazin);
                $produs_reducere->pret = $adaos;
                $produs_reducere->id_prod = 99996;
                $produs_reducere->nume =  'Adaos Rotunjire';
                $produs_reducere->cantitate = 1;
                $produs_reducere->intrare = 0;
                $produs_reducere->save();
            } elseif ($produs_reducere = $tab::on($user->magazin)->find(99996)) {
                $produs_reducere->pret = $adaos;
                $produs_reducere->save();
            }
        } elseif ($produs_reducere = $tab::on($user->magazin)->find(99996)) {
            $produs_reducere->delete();
        }
        if ($discount > 0) {

            if (!$tab::on($user->magazin)->find(99997)) {
                $produs_reducere = new $tab;
                $produs_reducere->setConnection($user->magazin);
                $produs_reducere->pret = -$discount;
                $produs_reducere->id_prod = 99997;
                $produs_reducere->nume  = 'Reducere Rotunjire';
                $produs_reducere->cantitate = 1;
                $produs_reducere->intrare = 0;
                $produs_reducere->save();
            } elseif ($produs_reducere = $tab::on($user->magazin)->find(99997)) {
                $produs_reducere->pret = -$discount;
                $produs_reducere->save();
            }
        } elseif ($produs_reducere = $tab::on($user->magazin)->find(99997)) {
            $produs_reducere->delete();
        }
        if ($adaos > 0) {
            return $adaos;
        }
        if ($discount > 0) {
            return -$discount;
        }
    }
}
