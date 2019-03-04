<?php
 //de refacut salvarea reducerii in sesiune bazata pe taburi!!
namespace App\Http\Controllers;

use App\Category;
use App\Image;
use App\Product;
use Auth;
use Config;

class ComandaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function getParentCats()
    {
        $parinti = array();
        $elem = array();
        $parents = Category::on('vapez')->where('active', 1)->distinct()->get(['id_parent'])->sortBy('nleft');
        foreach ($parents as $value) {
            array_push($parinti, $value->id_parent);
        }
        foreach ($parinti as $key => $value) {
            $categ = Category::on('vapez')->where('id_parent', $value)->get()->sortBy('nleft');
            $elem[$value] = array();
            foreach ($categ as $cat) {
                $elem[$value][$cat->id_category] = $cat->CategoryLang()->first()->name;
            }
        }
        return $elem;
    }

    public function index()
    {

        return view('comanda.index')->with('user', Auth::user());
    }

    public function sidebar($id)
    {
        if ($id) {

            $items = $this->getParentCats();
            $items = $items[$id];
            $items = json_encode($items);

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
        $categ = Category::on('vapez')->find($id);
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

    public function search($name)
    {
        $user = Auth::user();
        $products = Product::on($user->magazin)->where('nume', $name)->orWhere('nume', 'like', '%' . $name . '%')->get();
        return json_encode($products);
    }
}
