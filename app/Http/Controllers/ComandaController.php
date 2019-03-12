<?php
 //de refacut salvarea reducerii in sesiune bazata pe taburi!!
namespace App\Http\Controllers;

use App\Category;
use App\Image;
use App\Product;
use App\Comanda;
use App\Preturi_reselleri;
use App\Ps_product;
use App\Ps_order;
use App\Ps_order_detail;
use App\Ps_stock_available;
use App\Lista_reselleri;
use App\User;
use Auth;
use Config;
use Illuminate\Http\Request;
use function GuzzleHttp\json_encode;


class ComandaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

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
        $permisiuni_comenzi = Auth::user()->users_permisiuni->comanda;
        $reducere = Lista_reselleri::where('id_client', '=', Auth::user()->id_vapoint)->first()->reducere;
        $products_json = array();
        if ($permisiuni_comenzi) {
            $comanda = Comanda::on(Auth::user()->magazin)->get();
            //--------------------
            foreach ($comanda as $linie) {

                $stoc = Ps_stock_available::find($linie->id_prod);
                $permisiuni_viz_stocuri = Auth::user()->users_permisiuni->viz_stocuri_vap;
                $permisiuni_viz_preturi_res = Auth::user()->users_permisiuni->viz_preturi_res;
                $permisiuni_finalizare_comanda = Auth::user()->users_permisiuni->finalizare;

                $temp['nume'] = $linie->preturi_reselleri->nume;
                $temp['id'] = $linie->preturi_reselleri->id_produs;
                if ($stoc['quantity'] > 0) {
                    $temp['stoc'] = 'da';
                } else {
                    $temp['stoc'] = 'nu';
                }
                if ($permisiuni_viz_stocuri) {
                    $temp['stoc'] = $stoc['quantity'];
                }
                $temp['cos'] = $linie->cantitate;
                if ($linie->preturi_reselleri->$reducere > 0)
                    if ($permisiuni_viz_preturi_res) {
                        $temp['total_ctva'] = round($linie->preturi_reselleri->$reducere * 1.19 * $linie->cantitate, 2);
                        $temp['ctva'] = round($linie->preturi_reselleri->$reducere * 1.19, 2);
                        $temp['adaos_nr'] = round(($linie->preturi_reselleri->vct - ($linie->preturi_reselleri->$reducere * 1.19)) * $linie->cantitate, 2);
                        $temp['adaos_proc'] = round(($linie->preturi_reselleri->vct - $linie->preturi_reselleri->$reducere * 1.19) / $temp['ctva'] * 100, 2);
                        $products_json['viz_preturi'] = 1;
                    }
                if ($linie->preturi_reselleri->$reducere == 0) {
                    $temp['stoc'] = 'Nu este disponibil pt comanda!';
                }
                $image = Image::where([['id_product', $linie->id_prod], ['cover', '=', 1]])->first()->id_image;
                $temp['image'] = $image;
                $products_json['prods'][$linie->id_prod] = $temp;
            }
            //--------------------
            //var_dump($comanda);
            $json = json_encode($products_json);
            return view('comanda.index')->with('user', Auth::user())->with(
                ['comanda' => $products_json]
            );
        } else {
            return view('index')->with('user', Auth::user());
        }
    }

    public function sidebar(Request $request)
    {
        $permisiuni_comenzi = Auth::user()->users_permisiuni->comanda;
        if ($permisiuni_comenzi) {
            if ($request->id) {
                $items = $this->getParentCats($request->id);
                return $items;
            } else {
                $items = array();
                $items = json_encode($items);
                return $items;
            }
        } else {
            return view('index')->with('user', Auth::user());
        }
    }

    public function productList($id)
    {
        $permisiuni_comenzi = Auth::user()->users_permisiuni->comanda;
        $reducere = Lista_reselleri::where('id_client', '=', Auth::user()->id_vapoint)->first()->reducere;
        if ($permisiuni_comenzi) {

            $user = Auth::user();
            $categ = Category::on('vapez')->find($id);
            $products_json = array();
            $temp = array();
            $products = array();
            $products['products'] = $categ->ps_product;

            foreach ($categ->ps_product as $product) {
                if (Comanda::on(Auth::user()->magazin)->find($product->id_product)) {
                    $produs_comanda = Comanda::on(Auth::user()->magazin)->find($product->id_product);
                    $temp['cos'] = $produs_comanda->cantitate;
                } else {
                    $temp['cos'] = 0;
                }
                $reseller = Preturi_reselleri::find($product->id_product);
                $stoc = Ps_stock_available::find($product->id_product);
                $permisiuni_viz_stocuri = Auth::user()->users_permisiuni->viz_stocuri_vap;
                $permisiuni_viz_preturi_res = Auth::user()->users_permisiuni->viz_preturi_res;

                $temp['ref'] = $reseller->ref;
                $temp['id'] = $reseller->id_produs;
                $temp['nume'] = $reseller->nume;

                if ($stoc['quantity'] > 0) {
                    $temp['stoc'] = 'da';
                } else {
                    $temp['stoc'] = 'nu';
                }
                if ($permisiuni_viz_stocuri) {
                    $temp['stoc'] = $stoc['quantity'];
                }
                if ($reseller->$reducere > 0)
                    if ($permisiuni_viz_preturi_res) {
                        $temp['ftva'] = $reseller->$reducere;
                        $temp['ctva'] = round($reseller->$reducere * 1.19, 2);
                        $temp['site'] = $reseller->vct;
                        $temp['adaos_nr'] = round($reseller->vct - round($reseller->$reducere * 1.19, 2), 2);
                        $temp['adaos_proc'] = round(($temp['adaos_nr'] / $temp['ctva']) * 100, 2);
                        $products_json['viz_preturi'] = 1;
                    }
                if ($reseller->$reducere == 0) {
                    $temp['stoc'] = 'Nu este disponibil pt comanda!';
                }
                $products_json['prods'][$product->id_product] = $temp;
            }
        } else {
            return "Nu aveti permisiunile necesare!";
        }
        return json_encode($products_json);
    }
    public function search($name)
    {
        $user = Auth::user();
        $products = Product::on($user->magazin)->where('nume', $name)->orWhere('nume', 'like', '%' . $name . '%')->get();
        return json_encode($products);
    }

    public function showCos()
    { }
    public function addToCmd(Request $request)
    {
        $permisiuni_comenzi = Auth::user()->users_permisiuni->comanda;

        $permisiuni_viz_stocuri = Auth::user()->users_permisiuni->viz_stocuri_vap;

        $permisiuni_viz_preturi_res = Auth::user()->users_permisiuni->viz_preturi_res;
        $permisiuni_user_activ = Auth::user()->activ;

        $permisiuni_finalizare_comanda = Auth::user()->users_permisiuni->finalizare;

        if (($permisiuni_comenzi) && ($permisiuni_user_activ)) {

            $cantitate = $request->cantitate;
            $id = $request->id_prod;
            $products_json = array();
            $stoc = Ps_stock_available::find($id);
            $reducere = Lista_reselleri::where('id_client', '=', Auth::user()->id_vapoint)->first()->reducere;
            if (Comanda::on(Auth::user()->magazin)->find($id)) {
                $produs_comanda = Comanda::on(Auth::user()->magazin)->find($id);
            } else {
                $produs_comanda = new Comanda;
                $produs_comanda->setConnection(Auth::user()->magazin);
                $produs_comanda->id_prod = $id;
            }
            $produs_comanda->cantitate += $cantitate;
            if ($produs_comanda->cantitate == 0) {
                $produs_comanda->delete();
            } else {
                if (($stoc->quantity >= $produs_comanda->cantitate) && ($produs_comanda->cantitate >= 0)) {
                    $produs_comanda->save();
                }
            }
            if ($request->list) {

                $produs_comanda = Comanda::on(Auth::user()->magazin)->find($id);
                $temp['ref'] = $produs_comanda->preturi_reselleri->ref;
                $temp['id'] = $produs_comanda->preturi_reselleri->id_produs;
                $temp['nume'] = $produs_comanda->preturi_reselleri->nume;
                $temp['cos'] = $produs_comanda->cantitate;
                if ($stoc['quantity'] > 0) {
                    $temp['stoc'] = 'da';
                } else {

                    $temp['stoc'] = 'nu';
                }
                if ($permisiuni_viz_stocuri) {
                    $temp['stoc'] = $stoc['quantity'];
                }
                if ($produs_comanda->preturi_reselleri->$reducere > 0)
                    if ($permisiuni_viz_preturi_res) {
                        $temp['ftva'] = $produs_comanda->preturi_reselleri->$reducere;
                        $temp['ctva'] = round($produs_comanda->preturi_reselleri->$reducere * 1.19, 2);
                        $temp['site'] = $produs_comanda->preturi_reselleri->vct;
                        $temp['adaos_nr'] = round($produs_comanda->preturi_reselleri->vct - round($produs_comanda->preturi_reselleri->$reducere * 1.19, 2), 2);
                        $temp['adaos_proc'] = round(($temp['adaos_nr'] / $temp['ctva']) * 100, 2);
                        $products_json['viz_preturi'] = 1;
                    }
                if ($produs_comanda->preturi_reselleri->$reducere == 0) {
                    $temp['stoc'] = 'Nu este disponibil pt comanda!';
                }
                $products_json['prods'][$id] = $temp;
                return $products_json;
            }
            $comanda = Comanda::on(Auth::user()->magazin)->get();
            foreach ($comanda as $linie) {
                $stoc = Ps_stock_available::find($linie->id_prod);
                $temp['nume'] = $linie->preturi_reselleri->nume;
                $temp['id'] = $linie->preturi_reselleri->id_produs;
                if ($stoc['quantity'] > 0) {
                    $temp['stoc'] = 'da';
                } else {
                    $temp['stoc'] = 'nu';
                }
                if ($permisiuni_viz_stocuri) {
                    $temp['stoc'] = $stoc['quantity'];
                }
                $temp['cos'] = $linie->cantitate;
                if ($linie->preturi_reselleri->$reducere > 0)
                    if ($permisiuni_viz_preturi_res) {
                        $temp['total_ctva'] = round($linie->preturi_reselleri->$reducere * 1.19 * $linie->cantitate, 2);
                        $temp['ctva'] = round($linie->preturi_reselleri->$reducere * 1.19, 2);
                        $temp['adaos_nr'] = round(($linie->preturi_reselleri->vct - ($linie->preturi_reselleri->$reducere * 1.19)) * $linie->cantitate, 2);
                        $temp['adaos_proc'] = round(($linie->preturi_reselleri->vct - $linie->preturi_reselleri->$reducere * 1.19) / $temp['ctva'] * 100, 2);
                        $products_json['viz_preturi'] = 1;
                    }
                if ($linie->preturi_reselleri->$reducere == 0) {
                    $temp['stoc'] = 'Nu este disponibil pt comanda!';
                }
                $image = Image::where([['id_product', $linie->id_prod], ['cover', '=', 1]])->first()->id_image;
                $temp['image'] = $image;
                $products_json['prods'][$linie->id_prod] = $temp;
            }
            return json_encode($products_json);
        } else {
            return "Nu aveti permisiunile necesare!";
        }
    }

    public function rmCmd(Request $request)
    {

        $permisiuni_comenzi = Auth::user()->users_permisiuni->comanda;
        $id = $request->id_prod;
        if ($permisiuni_comenzi) {
            if ($request->list) {
                $prod_cmd = Comanda::on(Auth::user()->magazin)->find($request->id_prod);
                $prod_cmd->delete();
                $reducere = Lista_reselleri::where('id_client', '=', Auth::user()->id_vapoint)->first()->reducere;
                $reseller = Preturi_reselleri::find($id);
                $stoc = Ps_stock_available::find($id);
                $permisiuni_viz_stocuri = Auth::user()->users_permisiuni->viz_stocuri_vap;
                $permisiuni_viz_preturi_res = Auth::user()->users_permisiuni->viz_preturi_res;

                $temp['ref'] = $reseller->ref;
                $temp['id'] = $reseller->id_produs;
                $temp['nume'] = $reseller->nume;
                $temp['cos'] = 0;
                if ($stoc['quantity'] > 0) {
                    $temp['stoc'] = 'da';
                } else {

                    $temp['stoc'] = 'nu';
                }
                if ($permisiuni_viz_stocuri) {
                    $temp['stoc'] = $stoc['quantity'];
                }
                if ($reseller->$reducere > 0)
                    if ($permisiuni_viz_preturi_res) {
                        $temp['ftva'] = $reseller->$reducere;
                        $temp['ctva'] = round($reseller->$reducere * 1.19, 2);
                        $temp['site'] = $reseller->vct;
                        $temp['adaos_nr'] = round($reseller->vct - round($reseller->$reducere * 1.19, 2), 2);
                        $temp['adaos_proc'] = round(($temp['adaos_nr'] / $temp['ctva']) * 100, 2);
                        $products_json['viz_preturi'] = 1;
                    }
                if ($reseller->$reducere == 0) {
                    $temp['stoc'] = 'Nu este disponibil pt comanda!';
                }
                $products_json['prods'][$id] = $temp;
                return $products_json;
            } else {
                $reducere = Lista_reselleri::where('id_client', '=', Auth::user()->id_vapoint)->first()->reducere;
                $prod_cmd = Comanda::on(Auth::user()->magazin)->find($request->id_prod);
                $prod_cmd->delete();
                $products_json = array();
                $comanda = Comanda::on(Auth::user()->magazin)->get();
                //--------------------
                foreach ($comanda as $linie) {

                    $stoc = Ps_stock_available::find($linie->id_prod);
                    $permisiuni_viz_stocuri = Auth::user()->users_permisiuni->viz_stocuri_vap;
                    $permisiuni_viz_preturi_res = Auth::user()->users_permisiuni->viz_preturi_res;
                    $permisiuni_finalizare_comanda = Auth::user()->users_permisiuni->finalizare;

                    $temp['nume'] = $linie->preturi_reselleri->nume;
                    $temp['id'] = $linie->preturi_reselleri->id_produs;
                    if ($stoc['quantity'] > 0) {
                        $temp['stoc'] = 'da';
                    } else {
                        $temp['stoc'] = 'nu';
                    }
                    if ($permisiuni_viz_stocuri) {
                        $temp['stoc'] = $stoc['quantity'];
                    }
                    $temp['cos'] = $linie->cantitate;
                    if ($linie->preturi_reselleri->$reducere > 0)
                        if ($permisiuni_viz_preturi_res) {
                            $temp['total_ctva'] = round($linie->preturi_reselleri->$reducere * 1.19 * $linie->cantitate, 2);
                            $temp['ctva'] = round($linie->preturi_reselleri->$reducere * 1.19, 2);
                            $temp['adaos_nr'] = round(($linie->preturi_reselleri->vct - ($linie->preturi_reselleri->$reducere * 1.19)) * $linie->cantitate, 2);
                            $temp['adaos_proc'] = round(($linie->preturi_reselleri->vct - $linie->preturi_reselleri->$reducere * 1.19) / $temp['ctva'] * 100, 2);
                            $products_json['viz_preturi'] = 1;
                        }
                    if ($linie->preturi_reselleri->$reducere == 0) {
                        $temp['stoc'] = 'Nu este disponibil pt comanda!';
                    }
                    $image = Image::where([['id_product', $linie->id_prod], ['cover', '=', 1]])->first()->id_image;
                    $temp['image'] = $image;
                    $products_json['prods'][$linie->id_prod] = $temp;
                }

                return json_encode($products_json);
            }
        }
    }
    public function salveazaCmd(Request $request)
    {
        $permisiuni_comenzi = Auth::user()->users_permisiuni->comanda;

        $permisiuni_viz_stocuri = Auth::user()->users_permisiuni->viz_stocuri_vap;

        $permisiuni_viz_preturi_res = Auth::user()->users_permisiuni->viz_preturi_res;
        $permisiuni_user_activ = Auth::user()->activ;

        $permisiuni_finalizare_comanda = Auth::user()->users_permisiuni->finalizare;

        if ($permisiuni_comenzi && $permisiuni_user_activ && $permisiuni_finalizare_comanda) {
            $response = array();
            // $comanda_in_asteptare = Ps_order::where([['id_customer', '=', Auth::user()->id_vapoint], ['valid', '=', '1'], ['current_state', '=', '42']])->orderBy('id_order', 'DESC')->first();
            $comanda_in_asteptare = Ps_order::where([['valid', '=', '1'], ['current_state', '=', '42']])->orderBy('id_order', 'DESC')->first();
            if ($comanda_in_asteptare) {
                $message = "Comanda a fost cumulata cu comanda precedenta care se afla in asteptare si asteapta sa fie procesata.";
                $response['message'] = $message;
                $cos = Comanda::on(Auth::user()->magazin)->get();
                foreach ($cos as $prod) {

                    $ord_det = new Ps_order_detail;
                    $ord_det->id_shop = 1;
                    $ord_det->product_id = $prod->id_prod;
                    $ord_det->product_name = $prod->preturi_reselleri->nume;
                    $ord_det->quantity = $prod->cantitate;
                    $ord_det->product_quantity_in_stock = $prod->ps_stock_available->quantity;
                    $ord_det->product_price = //de terminat de salvat
                    //$comanda_in_asteptare->Ps_order_detail()->save($ord_det);
                }
            } else {
                $message = "Comanda a fost salvata si asteapta sa fie procesata.";
                $response['message'] = $message;
            }
            return  $response;
        }
    }
}
