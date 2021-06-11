<?php

namespace App\Http\Controllers;

use App\Models\OrdenPurchases;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TreeController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class WalletController extends Controller
{
    //

    public $treeController;

    public function __construct()
    {
        $this->treeController = new TreeController;
        View::share('titleq', 'Billetera');
    }

    /**
     * Lleva a la vista de la billetera
     *
     * @return void
     */
    public function index()
    {
        if (Auth::user()->admin == 1) {
            $wallets = Wallet::all();
        }else{
            $wallets = Auth::user()->getWallet;
        }
        return view('wallet.index', compact('wallets'));
    }

     /**
     * Lleva a la vista de pagos
     *
     * @return void
     */
    public function payments()
    {

        $payments = Wallet::where([['iduser', '=', Auth::user()->id], ['tipo_transaction', '=', '1']])->get();

        return view('wallet.payments', compact('payments'));
    }
    /**
     * Permite pagar las comisiones a los usuarios
     *
     * @param float $monto
     * @param integer $iduser
     * @param string $name_referred
     * @param integer $idcierre
     * @return void
     */
    public function payComision($monto, $iduser, $name_referred, $idcierre)
    {
        try {
            $sponsors = $this->treeController->getSponsor($iduser, [], 0, 'ID', 'referred_id');
            // dd($sponsors);
            if (!empty($sponsors)) {
                foreach ($sponsors as $sponsor) {
                    if ($sponsor->id != $iduser) {
                        $concepto = 'Comision del usuario '.$name_referred.' por un monto de '.$monto;
                        if ($sponsor->status == 1) {
                            if ($sponsor->nivel <= 4) {
                                $pocentaje = $this->getPorcentage($sponsor->nivel);
                                $comision = ($monto * $pocentaje);
                                $this->preSaveWallet($sponsor->id, $iduser, $idcierre, $comision, $concepto);
                            }else{
                                $this->preSaveWallet(2, $iduser, $idcierre, $monto, $concepto);
                            }
                        } else {
                            $this->preSaveWallet(2, $iduser, $idcierre, $monto, $concepto);
                        }   
                    }
                }
            }
        } catch (\Throwable $th) {
            Log::error('Wallet - payComision -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }
    }

    /**
     * Permita general el arreglo que se guardara en la wallet
     *
     * @param integer $iduser
     * @param integer $idreferido
     * @param integer $idorden
     * @param float $monto
     * @param string $concepto
     * @return void
     */
    private function preSaveWallet(int $iduser, int $idreferido, int $idorden,  float $monto, string $concepto)
    {
        $data = [
            'iduser' => $iduser,
            'referred_id' => $idreferido,
            'cierre_comision_id' => $idorden,
            'debito' => $monto,
            'descripcion' => $concepto,
            'status' => 0,
            'tipo_transaction' => 0,
        ];
        $this->saveWallet($data);
    }

    /**
     * Permite obtener el porcentaje a pagar
     *
     * @param integer $nivel
     * @return float
     */
    public function getPorcentage(int $nivel): float
    {
        $nivelPorcentaje = [
            1 => 0.20, 2 => 0.05, 3 => 0.02, 4 => 0.01, 5 => 0.02
        ];

        return $nivelPorcentaje[$nivel];
    }

    /**
     * Permite Recalcular el monto a pagar por el tipo de medio que recargo
     *
     * @param float $monto
     * @param string $tipo_pago
     * @return float
     */
    public function recalcularMonto(float $monto, string $tipo_pago):float
    {
        $arrayMetodo = [
            'payulatam' => 1.10, 'manual' => 1.00, 'stripe' => 1.10, 'coinbase' => 1.02
        ];
        
        $resultado = ($monto / $arrayMetodo[strtolower($tipo_pago)]);
        return $resultado;
    }

    /**
     * Permite obtener las compras de saldo de los ultimos 5 dias
     *
     * @param integer $iduser
     * @return object
     */
    public function getOrdens($iduser = null): object
    {
        try {
            $fecha = Carbon::now();
            if ($iduser == null) {
                $saldos = OrdenPurchases::where([
                    ['estado', '=', 1]
                ])->whereDate('created_at', '>=', $fecha->subDay(5))->get();
            }else{
                $saldos = OrdenPurchases::where([
                    ['iduser', '=', $iduser],
                    ['estado', '=', 1]
                ])->whereDate('created_at', '>=', $fecha->subDay(5))->get();
            }
            return $saldos;
        } catch (\Throwable $th) {
            Log::error('Wallet - getOrdes -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }
    }

    /**
     * Permite guardar la informacion de la wallet
     *
     * @param array $data
     * @return void
     */    
    public function saveWallet($data)
    {
        try {
            if ($data['iduser'] != 1) {
                if ($data['tipo_transaction'] == 1) {
                    $wallet = Wallet::create($data);
                    $saldoAcumulado = ($wallet->getWalletUser->wallet - $data['credito']);
                    $wallet->getWalletUser->update(['wallet' => $saldoAcumulado]);
                    $wallet->update(['balance' => $saldoAcumulado]);
                }else{
                    if ($data['cierre_comision_id'] != null) {
                        if ($data['iduser'] == 2) {
                            $wallet = Wallet::create($data);
                        }elseif($data['iduser'] > 2){
                            $check = Wallet::where([
                                ['iduser', '=', $data['iduser']],
                                ['cierre_comision_id', '=', $data['cierre_comision_id']]
                            ])->first();
                            if ($check == null) {
                                $wallet = Wallet::create($data);
                            }
                        }
                    }else{
                        $wallet = Wallet::create($data);
                    }
                    $saldoAcumulado = ($wallet->getWalletUser->wallet + $data['debito']);
                    $wallet->getWalletUser->update(['wallet' => $saldoAcumulado]);
                    $wallet->update(['balance' => $saldoAcumulado]);
                }
            }
        } catch (\Throwable $th) {
            Log::error('Wallet - saveWallet -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }
    }

    /**
     * Permite obtener el total disponible en comisiones
     *
     * @param integer $iduser
     * @return float
     */
    public function getTotalComision($iduser): float
    {
        try {
            $wallet = Wallet::where([['iduser', '=', $iduser], ['status', '=', 0]])->get()->sum('debito');
            if ($iduser == 1) {
                $wallet = Wallet::where([['status', '=', 0]])->get()->sum('debito');
            }
            return $wallet;
        } catch (\Throwable $th) {
            Log::error('Wallet - getTotalComision -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }
    }

    /**
     * Permite obtener el total de comisiones por meses
     *
     * @param integer $iduser
     * @return void
     */
    public function getDataGraphiComisiones($iduser)
    {
        try {
            $totalComision = [];
            if (Auth::user()->admin == 1) {
                $Comisiones = Wallet::select(DB::raw('SUM(debito) as Comision'))
                                ->where([
                                    ['status', '<=', 1]
                                ])
                                ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
                                ->orderBy(DB::raw('YEAR(created_at)'), 'ASC')
                                ->orderBy(DB::raw('MONTH(created_at)'), 'ASC')
                                ->take(6)
                                ->get();
            }else{
                $Comisiones = Wallet::select(DB::raw('SUM(debito) as Comision'))
                                ->where([
                                    ['iduser', '=',  $iduser],
                                    ['status', '<=', 1]
                                ])
                                ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
                                ->orderBy(DB::raw('YEAR(created_at)'), 'ASC')
                                ->orderBy(DB::raw('MONTH(created_at)'), 'ASC')
                                ->take(6)
                                ->get();
            }
            foreach ($Comisiones as $comi) {
                $totalComision [] = $comi->Comision;
            }
            return $totalComision;
        } catch (\Throwable $th) {
            Log::error('Wallet - getDataGraphiComisiones -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }
    }
}
