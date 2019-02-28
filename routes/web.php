<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/setup', 'SetupController@index')->name('setup');
Route::get('/galati', 'GalatiController@index')->name('galati');
Route::get('/romana', 'RomanaController@index')->name('romana');
Route::prefix('admin')->group(function () {
    Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
    Route::get('/', 'AdminController@index')->name('admin.dashboard');
    Route::post('/accounts', 'AdminController@getAccounts')->name('admin.get_accounts');
    Route::post('/createAdmin', 'AdminController@createAdmin')->name('admin.create_admin');
    Route::post('/createUser', 'AdminController@createUser')->name('admin.create_user');
    Route::post('/deleteUser', 'AdminController@deleteUser')->name('admin.delete_user');
    Route::post('/changeUsername', 'AdminController@changeUsername')->name('admin.change_username');
    Route::post('/changeName', 'AdminController@changeName')->name('admin.change_name');
    Route::post('/checked', 'AdminController@checked')->name('admin.checked');
    Route::post('/selectVap', 'AdminController@selectVap')->name('admin.select_vap');
    Route::post('/getService', 'AdminController@getService')->name('admin.get_service');
    Route::post('/getProduseIntrare', 'AdminController@getProduseIntrare')->name('admin.get_produse_intrare');
    Route::post('/getVapoints', 'AdminController@getVapoints')->name('admin.get_vapoints');
});
Route::prefix('casa')->group(function () {
    Route::get('/', 'CasaController@index')->name('casa.index');
    Route::get('/showcart', 'CasaController@show')->name('casa.show');
    Route::get('/sidebar_categ/{id}', 'CasaController@sidebar')->name('casa.sidebar');
    Route::get('/lista_produse/{id}', 'CasaController@productList')->name('casa.productList');
    Route::get('/cart_content/{id}', 'CasaController@addToCart')->name('casa.addToCart');
    Route::get('/increase_q/{id}', 'CasaController@increaseQ')->name('casa.incrQ');
    Route::get('/decrease_q/{id}', 'CasaController@decreaseQ')->name('casa.decrQ');
    Route::get('/get_tab', 'CasaController@get_tab')->name('casa.get_tab');
    Route::get('/set_tab/{tab}', 'CasaController@set_tab')->name('casa.set_tab');
    Route::post('/savesn', 'CasaController@saveSerial')->name('casa.saveSerial');
    Route::get('/checkWarranty/{id}', 'CasaController@checkWarranty')->name('casa.check_warranty');
    Route::get('/search/{name}', 'CasaController@search')->name('casa.search');
    Route::post('/incasare', 'CasaController@incasare')->name('casa.incasare');
    Route::post('/reducere_tot', 'CasaController@reducere_tot')->name('casa.redTot');
    Route::post('/aplicare_reduceri', 'CasaController@aplicare_reduceri')->name('casa.aplicareReduceri');
});
Route::prefix('stoc')->group(function () {

    Route::get('/', 'StocController@index')->name('stoc.index');
});
Route::prefix('garantii')->group(function () {
    Route::get('/', 'GarantiiController@index')->name('garantii.index');
    Route::post('/deschideBonuri', 'GarantiiController@deschideBonuri')->name('garantii.deschide_bon');
    Route::post('/primit', 'GarantiiController@intrareGarantie')->name('garantii.primit');
    Route::post('/primit_produs', 'GarantiiController@intrareProdus')->name('garantii.primit_produs');
    Route::post('/produse_intrare', 'GarantiiController@produseIntrare')->name('garantii.produse_intrare');
    Route::post('/primit_vap', 'GarantiiController@primitVap')->name('garantii.primit_vap');
    Route::post('/primit_service', 'GarantiiController@primitService')->name('garantii.primit_service');
    Route::get('/intrate', 'GarantiiController@garantiiIntrate')->name('garantii.intrate');
    Route::post('/rezolvat', 'GarantiiController@rezolvat')->name('garantii.rezolvat');
});
