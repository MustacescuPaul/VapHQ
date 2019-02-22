<?php
//de refacut salvarea reducerii in sesiune bazata pe taburi!!
namespace App\Http\Controllers;

use App\Category;
use App\Image;
use App\Product;
use Auth;
use Config;

class StocController extends Controller {
	public function __construct() {
		$this->middleware('auth:web');
	}

	public function switchDB($db) {
		$dm = app("Illuminate\\Database\\DatabaseManager");
		$dm->disconnect();

		Config::set("database.connections.mysql", [
			'driver' => 'mysql',
			'host' => '127.0.0.1',
			'port' => '3306',
			'database' => $db,
			'username' => 'root',
			'password' => '',
			'unix_socket' => env('DB_SOCKET', ''),
			'charset' => 'utf8mb4',
			'collation' => 'utf8mb4_unicode_ci',
			'prefix' => '',
			'prefix_indexes' => true,
			'strict' => true,
			'engine' => null,
		]);
	}

	public function getParentCats() {
		$parinti = array();
		$elem = array();
		$parents = Category::where('active', 1)->distinct()->get(['id_parent'])->sortBy('nleft');
		foreach ($parents as $value) {
			array_push($parinti, $value->id_parent);
		}
		foreach ($parinti as $key => $value) {
			$categ = Category::where('id_parent', $value)->get()->sortBy('nleft');
			$elem[$value] = array();
			foreach ($categ as $cat) {
				$elem[$value][$cat->id_category] = $cat->CategoryLang()->first()->name;
			}
		}
		return $elem;
	}

	public function index() {

		return view('casa.index')->with('user', Auth::user());
	}

	public function sidebar($id) {
		if ($id) {
			$this->switchDB('vapez');
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

	public function productList($id) {

		$user = Auth::user();
		$this->switchDB($user->magazin);
		$categ = Category::find($id);
		$products_json = array();
		$products_json['products'] = $categ->products;

		$this->switchDB('vapez');

		foreach ($categ->products as $product) {
			$images = Image::where('id_product', $product->id_prod)->get(['id_image']);
			$products_json['images'][$product->id_prod] = array();
			foreach ($images as $image) {
				array_push($products_json['images'][$product->id_prod], $image->id_image);
			}

		}

		return json_encode($products_json);

	}

	public function search($name) {
		$this->switchDB('vapez');

		$products = Product::where('nume', $name)->orWhere('nume', 'like', '%' . $name . '%')->get();
		return json_encode($products);
	}

}
