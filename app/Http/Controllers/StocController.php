<?php
 //de refacut salvarea reducerii in sesiune bazata pe taburi!!
namespace App\Http\Controllers;

use App\Category;
use App\Image;
use App\A_stock;
use App\User;
use Auth;
use Config;
use App\Preturi_reselleri;
use Illuminate\Http\Request;
use App\Comanda;
use App\Ps_stock_available;
use App\Lista_reselleri;
use App\Product;
use App\CategoryProduct;
use App\Ps_product;


class StocController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }
    public function index(Request $request)
    {
        $permisiuni_stoc = Auth::user()->users_permisiuni->stocuri;
        if ($permisiuni_stoc) {
            $modificari = [];
            $stocuri = A_stock::on(Auth::user()->magazin)->orderBy('date_add', 'desc')->paginate(20);
            foreach ($stocuri as $stoc) {
                $modificari['data'][$stoc->id]['data'] = date("d M 'y", strtotime($stoc->date_add));
                $modificari['data'][$stoc->id]['ora'] = date("H:i", strtotime($stoc->date_add));
                $modificari['data'][$stoc->id]['cantitate'] = $stoc->cantitate;
                $modificari['data'][$stoc->id]['motiv'] = $stoc->motiv;
                if (User::find($stoc->user))
                    $modificari['data'][$stoc->id]['user'] = User::find($stoc->user)->prenume . ' ' . User::find($stoc->user)->nume;
                else
                    $modificari['data'][$stoc->id]['user'] = "Prea vechi";
                if (Preturi_reselleri::find($stoc->id_product))
                    $produs = Preturi_reselleri::find($stoc->id_product);
                $modificari['data'][$stoc->id]['nume'] =  iconv("ISO-8859-1", "UTF-8", $produs->nume);
                $modificari['data'][$stoc->id]['ref'] = $produs->ref;
            }
            $modificari['meta']['pages'] = $stocuri->lastPage();

            if ($request->paginate == 1) {

                return $modificari;
            } else {
                return view('stoc.index')->with('user', Auth::user())->with(
                    ['modificari' => $modificari]
                );
            }
        } else {
            return view('index')->with('user', Auth::user());
        }
    }
    public function sidebar(Request $request)
    {
        $permisiuni_stocuri = Auth::user()->users_permisiuni->stocuri;
        if ($permisiuni_stocuri) {
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

    public function productList($id)
    {
        $permisiuni_stoc = Auth::user()->users_permisiuni->stocuri;
        $reducere = Lista_reselleri::where('id_client', '=', Auth::user()->id_vapoint)->first()->reducere;
        if ($permisiuni_stoc) {

            $user = Auth::user();
            $categ = Category::on('vapez')->find($id);
            $products = array();
            $temp = array();

            foreach ($categ->ps_product as $product) {
                $pos = CategoryProduct::where([['id_product', '=', $product->id_product], ['id_category', '=', $id]])->first()->position;
                if ($produs = Product::on(Auth::user()->magazin)->find($product->id_product))
                    $cantitate = $produs->stoc;
                else
                    $cantitate = 0;

                $products['data'][$pos]['id'] = $product->id_product;
                $products['data'][$pos]['ref'] = $product->reference;
                $products['data'][$pos]['nume'] = $product->ps_product_lang->where('id_lang', '=', 2)->first()->name;
                $products['data'][$pos]['stoc'] = $cantitate;
            }
        } else {
            return "Nu aveti permisiunile necesare!";
        }

        return json_encode($products);
    }
    public function modificaStoc(Request $request)
    {
        $request->validate([
            'id_prod' => 'required|numeric',
            'cantitate' => 'required|numeric',
            'motiv' => 'required|regex:/^[\pL\s\-]+$/u',

        ]);
        $produs = Product::on(Auth::user()->magazin)->find($request->id_prod);
        $stoc = $produs->stoc;
        if ($request->cantitate <= $stoc) {
            $produs->stoc += $request->cantitate;

            $produs->save();
            $modificare = new A_stock;
            $modificare->setConnection(Auth::user()->magazin);
            $modificare->id_product = (int)$request->id_prod;
            $modificare->cantitate = (int)$request->cantitate;
            $modificare->motiv = $request->motiv;
            $modificare->user = Auth::user()->id;
            $modificare->date_add = date("Y-m-d H:i:s");
            $modificare->save();
        }
    }

    public function search($name)
    {
        $permisiuni_stoc = Auth::user()->users_permisiuni->stocuri;

        if ($permisiuni_stoc) {

            $user = Auth::user();
            $prods = Preturi_reselleri::where('nume', $name)->orWhere('nume', 'like', '%' . $name . '%')->get()->sortByDesc('id_produs');
            $products = array();
            $temp = array();

            foreach ($prods as $product) {

                if ($produs = Product::on(Auth::user()->magazin)->find($product->id_produs))
                    $cantitate = $produs->stoc;
                else
                    $cantitate = 0;

                $products['data'][$product->id_produs]['id'] = $product->id_produs;
                $products['data'][$product->id_produs]['ref'] = $product->ref;
                $products['data'][$product->id_produs]['nume'] = $product->nume;
                $products['data'][$product->id_produs]['stoc'] = $cantitate;
            }
        }

        return json_encode($products);
    }
}
