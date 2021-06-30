<?php


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*+
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
    Route::get('getdatagraphicdashboard', 'ReporteController@graphisDashboard')->name('home.data.graphic');

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

    // Ruta para la pagos
    Route::prefix('payments')->group(function ()
    {
        Route::get('/', 'WalletController@payments')->name('payments.index');
    });

    // Ruta para la tienda
    Route::prefix('shop')->group(function ()
    {
        Route::get('/', 'TiendaController@index')->name('shop');
        Route::get('/groups/{idgroup}/products', 'TiendaController@products')->name('shop.products');
        Route::post('/procces', 'TiendaController@procesarOrden')->name('shop.procces');
        Route::post('/ipn', 'TiendaController@ipn')->name('shop.ipn');
        Route::get('{orden}/{status}/estado', 'TiendaController@statusProcess')->name('shop.proceso.status');
        Route::get('orden-history', 'TiendaController@ordenHistory')->name('shop.orden.history');
        Route::post('cambiarStatus', 'TiendaController@cambiar_status')->name('cambiarStatus');
    });

    // Ruta para las funciones por alla que no correspondan a otra seccion
    Route::prefix('ajax')->group(function ()
    {
        Route::get('/update/{side}/binary', 'HomeController@updateSideBinary')->name('ajax.update.side.binary');
    });

    
    //Ruta para los usuarios
    Route::prefix('user')->group(function(){
    
        Route::get('kyc', 'UserController@kyc')->name('kyc');

        Route::get('profile', 'UserController@editProfile')->name('profile');

        Route::get('user-list', 'UserController@listUser')->name('users.list-user')->middleware('auth', 'checkrole:1');
        Route::get('user-edit/{id}', 'UserController@editUser')->name('users.edit-user');
        Route::get('user-show/{id}', 'UserController@showUser')->name('users.show-user');
        Route::patch('user-verify/{id}', 'UserController@verifyUser')->name('users.verify-user');
        Route::patch('user-update/{id}', 'UserController@updateUser')->name('users.update-user');
        Route::delete('user/delete/{id}','UserController@destroyUser')->name('users.destroy-user');

        Route::patch('profile-update', 'UserController@updateProfile')->name('profile.update');
        Route::patch('profile-update-kyc', 'UserController@updateProfileKYC')->name('profile.update.kyc');

        Route::post('change-password', 'ChangePasswordController@store')->name('change.password');
        Route::get('change-password', 'ChangePasswordController@change-password')->name('profile.change-password');

        Route::get('/impersonate/stop', 'ImpersonateController@stop')->name('impersonate.stop');
        Route::post('/impersonate/{user}/start', 'ImpersonateController@start')->name('impersonate.start');
    });

    Route::prefix('inversiones')->group(function ()
    {
        Route::get('/{tipo?}/lists', 'InversionController@index')->name('inversiones.index');
        Route::get('/cambiarStatus', 'InversionController@checkStatus')->name('inversiones.checkStatus');
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

        //Rutas para el cierre de productos
        Route::prefix('accounting')->group(function(){
            Route::resource('commission_closing', 'CierreComisionController');
            Route::get('pagarComisiones', 'CierreComisionController@pagarUtilidadFinDeMes')->name('pagarComisiones');
        });

        //Rutas para los reportes
        Route::prefix('reports')->group(function(){
            Route::get('purchase', 'ReporteController@indexPedidos')->name('reports.pedidos');
            Route::get('commission', 'ReporteController@indexComision')->name('reports.comision');

        });

        
    });

});
