<?php

namespace App\Http\Controllers;
use App\Intrare;
use App\Intrare_produs;
use App\User;
use App\Vapoint;
use App\Vapointhq;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware('auth:admin');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {

		return view('admin.index')->with('user', Auth::user());
	}

	public function getAccounts() {
		$vaps = array();
		$users = User::all();
		$vapoints = Vapointhq::all('id', 'nume');
		foreach ($vapoints as $vapoint) {
			$vaps[$vapoint->id] = $vapoint->nume;
		}
		$array['vapoints'] = $vaps;
		$array['users'] = $users;
		return json_encode($array);
	}

	public function createAdmin(Request $request) {
		$request->validate([
			'username' => 'required|unique:mysql.users,username|max:50|alpha_dash',
			'parola' => 'required|max:50|alpha_dash',

		]);
		$admin = new Admin;
		$admin->username = $request->username;
		$admin->password = Hash::make($request->parola);
		$user->save();

	}
	public function createUser(Request $request) {
		$request->validate([
			'username' => 'required|unique:mysql.users,username|max:50|alpha_dash',
			'nume' => 'required|max:50|alpha_dash',
			'prenume' => 'required|max:50|alpha_dash',
			'parola' => 'required|max:50|alpha_dash',

		]);

		$user = new User;
		$user->username = $request->username;
		$user->prenume = $request->prenume;
		$user->nume = $request->nume;
		$user->password = Hash::make($request->parola);

		$user->save();
	}

	public function deleteUser(Request $request) {
		$user = User::find($request->id);
		$user->delete();

		$users = User::all();
		return json_encode($users);

	}

	public function changeUsername(Request $request) {
		$request->validate([
			'username' => 'required|unique:mysql.users,username|max:50|alpha_dash',
		]);

		$user = User::find($request->id);
		$user->username = $request->username;
		$user->save();
		$users = User::all();
		return json_encode($users);

	}
	public function changeName(Request $request) {
		$request->validate([
			'name' => 'required|max:50|alpha_dash',
		]);

		$user = User::find($request->id);
		$user->nume = $request->name;
		$user->save();
		$users = User::all();
		return json_encode($users);

	}

	public function checked(Request $request) {
		$request->validate([
			'value' => 'required|bool',
		]);

		$user = User::find($request->id);
		if ($request->name == 'activ') {
			if ($request->value == true) {
				$user->activ = true;
			} elseif ($request->value == false) {
				$user->activ = false;
			}
		}
		if ($request->name == 'bonus') {
			if ($request->value == true) {
				$user->bonus = true;
			} elseif ($request->value == false) {
				$user->bonus = false;
			}
		}
		if ($request->name == 'banca') {
			if ($request->value == true) {
				$user->banca = true;
			} elseif ($request->value == false) {
				$user->banca = false;
			}
		}

		$user->save();
		$users = User::all();
		return json_encode($users);

	}
	public function selectVap(Request $request) {
		$request->validate([
			'vapoint' => 'required|regex:/^[\pL\s\-]+$/u',
		]);

		$vapoint = Vapointhq::where('nume', '=', $request->vapoint)->first();
		$user = User::find($request->id);
		$user->magazin = $vapoint->db;
		$user->id_vapoint = $vapoint->id;
		$user->save();
	}

	public function getService(Request $request) {
		if ($request->vap) {
			$intrari = Intrare::on('garantii')->where('status', '=', 'Primit @ ' . $request->vap)->get();
			return json_encode($intrari);
		} else {
			$intrari = Intrare::all();
			return json_encode($intrari);
		}
	}

	public function getProduseIntrare(Request $request) {
		$intrari = Intrare_produs::on('garantii')->where('id_service', '=', $request->id)->get();
		return json_encode($intrari);
	}
	public function getVapoints() {
		$vapoints = Vapoint::on('garantii')->get(["nume"]);
		return json_encode($vapoints);
	}
}
