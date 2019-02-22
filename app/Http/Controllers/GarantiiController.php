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

class GarantiiController extends Controller {
	public function index() {
		return view('garantii.index')->with('user', Auth::user());

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

	public function deschideBonuri(Request $request) {
		$user = Auth::user();
		if ($request->cod_bon) {

			$this->switchDB($user->magazin);
			$bon = Bon::where('cod', '=', $request->cod_bon)->first();
			$cart = array();
			$c = $bon->detaliu_bonuri;

			foreach ($c as $key => $product) {
				$this->switchDB('garantii');
				if (Produse::on('garantii')->where('id_prod', '=', $product->id_prod)->exists()) {
					$produs = Produse::on('garantii')->where('id_prod', '=', $product->id_prod)->first();
					$sn = $produs->cod;
					$this->switchDB('vapez');
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
			$this->switchDB('garantii');
			$produs = Produse::where('cod', '=', $request->serial_number)->first();
			$this->switchDB($user->magazin);
			$product = Product::find($produs->id_prod);
			$this->switchDB('vapez');
			if ($images = Image::where([['id_product', $product->id_prod], ['cover', '1']])->first()) {
				$cart[0] = ['img' => $images->id_image, 'name' => $product->nume, 'id_prod' => $product->id_prod, 'sn' => $request->serial_number];
			} else {
				$cart[0] = ['img' => '', 'name' => $product->nume, 'id_prod' => $product->id_prod, 'sn' => $request->serial_number];
			}
			return json_encode($cart);
		}
		if ($request->cod_garantie) {

			$this->switchDB('garantii');
			$c = Produse::where('id_garantie', '=', $request->cod_garantie)->get();

			foreach ($c as $key => $prod) {

				$sn = $prod->cod;
				$this->switchDB('vapez');
				if ($images = Image::where([['id_product', $prod->id_prod], ['cover', '1']])->first()) {
					$cart[$key] = ['img' => $images->id_image, 'name' => $prod->nume, 'id_prod' => $prod->id_prod, 'sn' => $sn];
				} else {
					$cart[$key] = ['img' => '', 'name' => $prod->nume, 'id_prod' => $prod->id_prod, 'sn' => $sn];
				}
			}

		}
		return json_encode($cart);

	}

	public function intrareGarantie(Request $request) {
		$this->switchDB('garantii');

		$garantie = Produse::on('garantii')->where([['cod', '=', $request->sn], ['id_prod', '=', $request->id_prod]])->first()->garantii;

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
	public function intrareProdus(Request $request) {
		$this->switchDB('garantii');
		// id_vanzare este id din tab produse
		$garantie = Produse::on('garantii')->where([['cod', '=', $request->sn], ['id_prod', '=', $request->id]])->first()->garantii;
		$produs = Produse::on('garantii')->where([['cod', '=', $request->sn], ['id_prod', '=', $request->id]])->first();
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
		//de facut interfata pentru completat acese date in vuejs
	}

	public function garantiiIntrate() {
		$user = Auth::user();
		$this->switchDB('garantii');
		$intrari = Intrare::where('id_vapoint', '=', $user->id_vapoint)->get();

		return view('garantii.intrate')->with('user', $user)->with('intrari', $intrari);
	}

	public function produseIntrare(Request $request) {
		$this->switchDB('garantii');
		$produse = Intrare_produs::where('id_service', '=', $request->id_intrare)->get();
		return json_encode($produse);
	}

	public function primitVap(Request $request) {
		$user = Auth::user();
		if ($request->stat == "Expediat") {
			$vapoint = Vapoint::on('garantii')->find($user->id_vapoint);
			$status = "Expediat @ " . $vapoint->nume;
			$intrare = Intrare::on('garantii')->find($request->id);
			$intrare->status = $status;
			$intrare->save();

		}
		if ($request->stat == "Returnat") {

			$vapoint = Vapoint::on('garantii')->find($user->id_vapoint);
			$intrare = Intrare::on('garantii')->find($request->id);
			$intrare->status = 'Returnat @ ' . $vapoint->nume;
			$intrare->save();
		}
		$intrari = Intrare::where('id_vapoint', '=', $user->id_vapoint)->get();
		return json_encode($intrari);

	}

	public function primitService(Request $request) {
		$user = Auth::user();
		if ($request->stat == "Expediat") {
			$status = "Expediat @ Service";
			$intrare = Intrare::on('garantii')->find($request->id);
			$intrare->status = $status;
			$intrare->save();

		}
		if ($request->stat == "Primit") {
			$intrare = Intrare::on('garantii')->find($request->id);
			$intrare->status = 'Primit @ Service';
			$intrare->save();
		}
		$intrari = Intrare::on('garantii')->get();
		return json_encode($intrari);

	}
	public function rezolvat(Request $request) {
		$request->validate([
			'text' => 'required|alpha_dash|max:255',
			'id' => 'required|numeric',
		]);
		$intrare = Intrare::on('garantii')->find($request->id);
		$intrare->remediat = 1;

		$produs = Intrare_produs::on('garantii')->where('id_service', '=', $intrare->id)->first();
		$produs->remediere = $request->text;
		$produs->save();
		$intrare->save();

	}

}
