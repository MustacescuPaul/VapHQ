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
use App\Vapoint;
use Auth;
use Config;
use Illuminate\Http\Request;

class CasaController extends Controller {
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
		$this->switchDB('vaphq');
		$tab = session('tab');
		if (!$tab) {
			session(['tab' => 'Tab1']);
		}

		$this->show();
		return view('casa.index')->with('user', Auth::user());
	}

	public function show() {

		$user = Auth::user();

		$this->switchDB($user->magazin);

		$tab = 'App\\' . session('tab');
		$cart = $tab::on($user->magazin)->where('id_prod', '!=', 0)->get();

		if ($cart->isEmpty()) {
			$tab::truncate();
		}

		$this->switchDB('vapez');
		foreach ($cart as $key => $product) {
			$images = Image::where([['id_product', $product->id_prod], ['cover', '1']])->get(['id_image']);
			foreach ($images as $image) {
				$cart[$key]['img'] = $image->id_image;
			}
		}

		return json_encode($cart);

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

	public function get_tab() {
		$tab = session('tab');
		return json_encode($tab);
	}
	public function set_tab($tab) {
		if (($tab == 'Tab1') || ($tab == 'Tab2') || ($tab == 'Tab3') || ($tab == 'Tab4') || ($tab == 'Tab5') || ($tab == 'Tab6')) {
			session(['tab' => $tab]);
		}

		return redirect()->route('casa.index');
	}

	public function addToCart($id_prod) {

		$user = Auth::user();
		$this->switchDB($user->magazin);
		$prod = Product::find($id_prod);

		$tab = 'App\\' . session('tab');
		if (!$tab::find($id_prod)) {
			$cart = new $tab;
			$cart->id_prod = $prod->id_prod;
			$cart->pret = $prod->pret;
			$cart->nume = $prod->nume;
			$cart->cantitate = '1';
			$cart->intrare = $prod->intrare;
			$cart->sn = $this->checkWarranty($id_prod);
			$this->switchDB($user->magazin);
			$cart->save();
		} elseif ($tab::find($id_prod)) {
			$prod = $tab::find($id_prod);
			$prod->cantitate += 1;
			$prod->save();
		}

		//$this->index();
	}

	public function increaseQ($id_prod) {

		$user = Auth::user();

		$this->switchDB($user->magazin);
		$prod = Product::find($id_prod);

		$tab = 'App\\' . session('tab');
		$cart = $tab::find($id_prod);
		$cart->cantitate += '1';
		$cart->save();

		return json_encode($cart->cantitate);
		// $this->index();
	}

	public function decreaseQ($id_prod) {

		$user = Auth::user();
		$this->switchDB($user->magazin);
		$prod = Product::find($id_prod);

		$tab = 'App\\' . session('tab');
		$cart = $tab::find($id_prod);
		if ($cart->cantitate - 1 < 1) {
			$cart->delete();
			$cart = $tab::where('id_prod', '!=', 0)->get();
			if ($cart->isEmpty()) {
				$tab::truncate();
			}
			return json_encode('0');
		} else {
			$cart->cantitate -= '1';
			$cart->save();
		}
		$cart = $tab::where('id_prod', '!=', 0)->get();
		if ($cart->isEmpty()) {
			$tab::truncate();
		}

		return json_encode($cart->cantitate);
	}

	public function checkWarranty($id_prod) {
		$this->switchDB('garantii');
		$garantie = A_garantie::find($id_prod);
		if ($garantie) {
			return $garantie->garantie;
		} else {
			return 0;
		}

	}
	public function saveSerial(Request $request) {
		$request->validate([
			'id_prod' => 'required|numeric',
			'serial' => 'required|alpha_dash',
		]);
		$user = Auth::user();
		$this->switchDB($user->magazin);
		$tab = 'App\\' . session('tab');
		$prod = $tab::find($request->id_prod);

		$prod->sn = $request->serial;
		$prod->save();

	}
	public function search($name) {
		$this->switchDB('vapez');

		$products = Product::where('nume', $name)->orWhere('nume', 'like', '%' . $name . '%')->get();
		return json_encode($products);
	}

	public function incasare(Request $request) {
		$user = Auth::user();
		if ($request->cugarantie == 1) {
			$request->validate([
				'email' => 'required|email',
				'nume' => 'required|max:50|regex:/^[\pL\s]+$/u',
				'adresa' => 'required|max:100|regex:/^[\pL\s]+$/u',
				'telefon' => 'required|numeric',

			]);

			$this->switchDB('garantii');
			$vapoint = Vapoint::find($user->id_vapoint);

			$garantie = new Garantii;
			$garantie->id_vapoint = $vapoint->id;
			$garantie->nume_vapoint = $vapoint->nume;
			$garantie->nume_client = $request->nume;
			$garantie->adresa_client = $request->adresa;
			$garantie->telefon_client = $request->telefon;
			$garantie->email_client = $request->email;
			$garantie->save();

			$this->switchDB($user->magazin);

			$tab = 'App\\' . session('tab');
			$cart = $tab::where('sn', '>', 0)->get();
			$this->switchDB('garantii');

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
			$this->switchDB($user->magazin);

		}
		$this->switchDB($user->magazin);
		if ($request->reducere > 0) {
			$tab = 'App\\' . session('tab');
			if (!$tab::find(0)) {
				$produs_reducere = new $tab;
				$produs_reducere->pret = $request->reducere;
				$produs_reducere->id_prod = 0;
				$produs_reducere->nume = 'reducere';
				$produs_reducere->cantitate = 1;
				$produs_reducere->intrare = $request->reducere;
				$produs_reducere->save();
			} else {
				$produs_reducere = $tab::find(0);
				$produs_reducere->pret = $request->reducere;
				$produs_reducere->id_prod = 0;
				$produs_reducere->nume = 'reducere';
				$produs_reducere->cantitate = 1;
				$produs_reducere->intrare = $request->reducere;
				$produs_reducere->save();
			}
		}

		if ($request->reducere == 0) {
			$tab = 'App\\' . session('tab');
			if (!$tab::find(0)) {
				$produs_reducere = new $tab;
				$produs_reducere->pret = 0;
				$produs_reducere->id_prod = 0;
				$produs_reducere->nume = 'reducere';
				$produs_reducere->cantitate = 1;
				$produs_reducere->intrare = 0;
				$produs_reducere->save();
			}
		}

		if ($request->metoda) {

			$this->switchDB($user->magazin);
			$tab = 'App\\' . session('tab');
			$cart = $tab::where('id_prod', '!=', 0)->get();
			$bon = new Bon;
			$produs_reducere = $tab::where('nume', '=', 'reducere')->first();

			$sum_pret = $cart->sum('pret');
			$sum_intrare = $cart->sum('intrare');
			$bon->angajat = $user->id;
			$bon->intrare = $sum_intrare;

			$intreg = (int) $sum_pret;
			$dec = $sum_pret - $intreg;
			$adaos = 0;
			$discount = 0;
			if ($sum_pret > 10) {
				if ($dec == 0) {
					$bon->reducere = 0;
					$bon->platit = $sum_pret;

				}

				if ($dec == 0.5) {
					$bon->reducere = 0;
					$bon->platit = $sum_pret;
				}

				if ($dec < 0.25) {
					$discount = $dec;
				}

				if ($dec >= 0.25 AND $dec < 0.5) {
					$adaos = 0.5 - $dec;
				}

				if ($dec > 0.5 AND $dec < 0.75) {
					$discount = $dec - 0.5;
				}

				if ($dec >= 0.75) {
					$adaos = 1 - $dec;
				}
			} else {
				if ($dec == 0) {
					$bon->reducere = 0;
					$bon->platit = $sum_pret;
				}

				if ($dec == 0.5) {
					$bon->reducere = 0;
					$bon->platit = $sum_pret;
				}

				if ($dec < 0.5) {
					$adaos = 0.5 - $dec;
				}

				if ($dec > 0.5) {
					$adaos = 1 - $dec;
				}
			}
			if ($adaos > 0) {
				$bon->reducere = $adaos;
				$bon->platit = $sum_pret + $adaos;
				if ($produs_reducere->pret > 0) {

					$bon->reducere = $adaos - $produs_reducere->pret;
					$bon->platit = $bon->platit - $produs_reducere->pret;
				}

			}

			if ($discount > 0) {
				$bon->reducere = -$discount;
				$bon->platit = $sum_pret - $discount;
				if ($produs_reducere->pret > 0) {
					$bon->reducere = -$discount - $produs_reducere->pret;
					$bon->platit = $bon->platit - $produs_reducere->pret;
				}

			}
			if ($produs_reducere->pret > 0) {
				$bon->reducere = $produs_reducere->pret;
				$bon->platit = $bon->platit - $produs_reducere->pret;
			}

			if ($request->metoda == 'cash') {
				$bon->cash = $bon->platit;
				var_dump($bon->cash);
				$bon->card = 0;
			}
			if ($request->metoda == 'card') {
				$bon->card = $bon->platit;
				var_dump($bon->card);

				$bon->cash = 0;
			}

			$bon->refacut = 0;
			$bon->cod = rand(10000000, 99999999);
			$bon->livrat = 0;
			$bon->anulata = 0;

			$bon->save();
			$tab::truncate();

			$bon = Bon::where('intrare', $sum_intrare)->orderBy('id_bon', 'desc')->first();

			foreach ($cart as $product) {
				$detaliu = new Detaliu_bonuri;
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

	public function reducere_tot(Request $request) {
		$sum_pret = (double) $request->pret;
		$intreg = (int) $sum_pret;
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

			if ($dec >= 0.25 AND $dec < 0.5) {
				$adaos = 0.5 - $dec;
			}

			if ($dec > 0.5 AND $dec < 0.75) {
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
		if ($adaos > 0) {
			return $adaos;
		}
		if ($discount > 0) {
			return -$discount;
		}
	}

}
