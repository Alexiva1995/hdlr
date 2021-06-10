<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\OrdenPurchases;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\TreeController;
use App\Http\Controllers\WalletController;

class HomeController extends Controller
{
    public $treeController;
    public $ticketController;
    public $servicioController;
    public $addsaldoController;
    public $walletController;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->treeController = new TreeController;

        $this->walletController = new WalletController;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        try {
            View::share('titleg', '');
            $rewards = Wallet::where([['iduser', '=', Auth::user()->id], ['status', '=', '1']])->get()->sum('debito');
            $data = $this->dataDashboard(Auth::id());
            return view('dashboard.index', compact('data', 'rewards'));
        } catch (\Throwable $th) {
            Log::error('Home - index -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }
    }

    public function indexUser()
    {

        try {
            
            View::share('titleg', '');
            $rewards = Wallet::where([['iduser', '=', Auth::user()->id], ['status', '=', '0']])->get()->sum('debito');
            $packages = OrdenPurchases::where([['iduser', '=', Auth::user()->id], ['status', '=', '0']])->get();
            $data = $this->dataDashboard(Auth::id());
            return view('dashboard.indexUser', compact('data', 'rewards', 'packages'));
        } catch (\Throwable $th) {
            Log::error('Home - indexUser -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }
    }

    /**
     * Permite obtener la informacion a mostrar en el dashboard
     *
     * @param integer $iduser
     * @return array
     */
    public function dataDashboard(int $iduser):array
    {
        $cantUsers = $this->treeController->getTotalUser($iduser);
        $data = [
            'directos' => $cantUsers['directos'],
            'indirectos' => $cantUsers['indirectos'],
            'wallet' => Auth::user()->wallet,
            'comisiones' => $this->walletController->getTotalComision($iduser),
            'tickets' => 0,
            'ordenes' => 0,
            'usuario' => Auth::user()->fullname
        ];

        return $data;
    }

    /**
     * Permite actualizar el lado a registrar de un usuario
     *
     * @param string $side
     * @return void
     */
    public function updateSideBinary($side): string
    {
        try {
            DB::table('users')->where('id', Auth::id())->update(['binary_side_register' => $side]);
            return json_encode('bien');
        } catch (\Throwable $th) {
            Log::error('Home - indexUser -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }
    }

    /**
     * Permite obtener la informacion para las graficas del dashboard
     *
     * @return string
     */
    public function getDataGraphic(): string
    {
        try {
            $iduser = Auth::id();
            $data = [
                'comisiones' => $this->walletController->getDataGraphiComisiones($iduser),
                'tickets' => [],
                'saldo' => [],
                'ordenes' => []
            ];
            
            return json_encode($data);
        } catch (\Throwable $th) {
            Log::error('Home - getDataGraphic -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }
    }

    /**
     * Lleva a la vista de terminos y condiciones
     *
     * @return void
     */
    public function terminosCondiciones()
    {
        View::share('titleg', 'Terminos y Condiciones');
        return view('terminos_condiciones.index');
    }
}
