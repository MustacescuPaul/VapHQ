<?php
 //de refacut salvarea reducerii in sesiune bazata pe taburi!!
namespace App\Http\Controllers;

use App\Category;
use App\Image;
use App\Product;
use App\Comanda;
use App\Preturi_reselleri;
use App\Ps_product;
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
        if ($permisiuni_comenzi) {
            $comanda = Comanda::on(Auth::user()->magazin)->get();
            //--------------------
            foreach ($comanda as $linie) {

                $stoc = Ps_stock_available::find($linie->id_prod);
                $permisiuni_viz_stocuri = Auth::user()->users_permisiuni->viz_stocuri_vap;
                $permisiuni_viz_preturi_res = Auth::user()->users_permisiuni->viz_preturi_res;
                $permisiuni_finalizare_comanda = Auth::user()->users_permisiuni->finalizare;

                $temp['nume'] = $linie->preturi_reselleri->nume;
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
                        $temp['total_ctva'] = round($linie->preturi_reselleri->$reducere * 1.19, 2) * $linie->cantitate;
                        $temp['ctva'] = round($linie->preturi_reselleri->$reducere * 1.19, 2);
                        $temp['adaos_nr'] = round($linie->preturi_reselleri->vct - ($linie->preturi_reselleri->$reducere * 1.19) * $linie->cantitate, 2);
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
}
