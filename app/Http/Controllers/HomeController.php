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
use App\Http\Controllers\ActivacionController;
use App\Http\Controllers\TiendaController;

class HomeController extends Controller
{
    public $reportController;
    public $treeController;
    public $activacionController;
    public $servicioController;
    public $addsaldoController;
    public $walletController;
    public $tiendaController;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->tiendaController = new TiendaController();
        $this->treeController = new TreeController;
        $this->reportController = new ReporteController();
        $this->walletController = new WalletController;
        $this->activacionController = new ActivacionController();
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
            $this->activacionController->activarUser();
            $this->activacionController->deleteUser();
            $this->tiendaController->getOrdenes();
            $data = $this->dataDashboard(Auth::id());
            return view('dashboard.index', compact('data'));
        } catch (\Throwable $th) {
            Log::error('Home - index -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }
    }

    public function indexUser()
    {

        try {
            View::share('titleg', '');
            $data = $this->dataDashboard(Auth::id());
            return view('dashboard.indexUser', compact('data'));
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
            'ordenes' => $this->reportController->getOrdenes(10),
            'usuario' => Auth::user()->fullname,
            'rewards' => Wallet::where([['iduser', '=', $iduser], ['status', '=', '0']])->get()->sum('monto'),
            'packages' => OrdenPurchases::where([['iduser', '=', $iduser], ['status', '=', '0']])->get()
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
