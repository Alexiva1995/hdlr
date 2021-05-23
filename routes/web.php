<?php


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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



Route::get('/', function () {return view('welcome');})->name('mantenimiento');

Auth::routes();

Route::prefix('dashboard')->middleware('menu', 'auth')->group(function ()
{

    // Inicio
    Route::get('/home', 'HomeController@index')->name('home');
     // Inicio de usuarios
    Route::get('/home-user', 'HomeController@indexUser')->name('home.user');
    // Ruta para obtener la informacion de la graficas del dashboard
    Route::get('getdatagraphicdashboard', 'HomeController@getDataGraphic')->name('home.data.graphic');

    // Red de usuario
    Route::prefix('genealogy')->group(function ()
    {
        // Ruta para ver la lista de usuarios
        Route::get('users/{network}', 'TreeController@indexNewtwork')->name('genealogy_list_network');
        // Ruta para visualizar el arbol o la matriz
        Route::get('{type}', 'TreeController@index')->name('genealogy_type');
        // Ruta para visualizar el arbol o la matriz de un usuario en especifico
        Route::get('{type}/{id}', 'TreeController@moretree')->name('genealogy_type_id');
    });

    // Ruta para la billetera
    Route::prefix('wallet')->group(function ()
    {
        Route::get('/', 'WalletController@index')->name('wallet.index');
    });

    Route::prefix('shop')->group(function ()
    {
        Route::get('/', 'TiendaController@index')->name('shop');
        Route::get('/groups/{idgroup}/products', 'TiendaController@products')->name('shop.products');
    });

    /**
     * Seccion del sistema para el admin
     */
    Route::prefix('admin')->middleware('checkrole')->group(function ()
    {
   
        //Agregar servicios
        Route::prefix('products')->group(function ()
        {
            //Rutas para los grupos 
            Route::resource('group', 'GroupsController');
            //Rutas para los paquetes
            Route::resource('package', 'PackagesController');
        }); 

         //Ruta de liquidacion 
        Route::prefix('settlement')->group(function() 
        {
            //Ruta liquidaciones realizadas
            Route::get('/', 'LiquidactionController@index')->name('settlement');
            Route::get('/pending', 'LiquidactionController@indexPendientes')->name('settlement.pending');
            Route::post('/process', 'LiquidactionController@procesarLiquidacion')->name('settlement.process');
            Route::get('/{status}/history', 'LiquidactionController@indexHistory')->name('settlement.history.status');
            Route::resource('liquidation', 'LiquidactionController');
        });

        
    });

});
