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
use App\Http\Controllers\InversionController;
use App\Models\CierreComision;
use App\Models\Inversion;
use App\Models\User;

class WalletController extends Controller
{
    //

    public $treeController;
    public $inversionController;

    public function __construct()
    {
        $this->inversionController = new InversionController;
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

        //$saldoDisponible = $wallets->where('status', 0)->sum('monto');
        $saldoDisponible = 0;

        foreach($wallets->where('status', 0) as $monto){
            if($monto->tipo_transaction == 1){

                $monto->monto = $monto->monto * (-1);
        
            }
            $saldoDisponible+= $monto->monto;
        }
        return view('wallet.index', compact('wallets', 'saldoDisponible'));
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
    public function payComision($monto, $iduser, $name_referred, $inversion_id=null, $orden_id=null, $package_id=null)
    {
        //try {
            $ultimoNivel = 0;
            $comisionAcumulada = 0;
            $user = User::findOrFail(1);

            $sponsors = $this->treeController->getSponsor($iduser, [], 0, 'ID', 'referred_id');
            // dd($sponsors);
            if (!empty($sponsors)) {
                foreach ($sponsors as $sponsor) {
                    if ($sponsor->id != $iduser) {
                        $concepto = 'Comision del usuario '.$name_referred.' por un monto de '.$monto;
                        $pocentaje = $this->getPorcentage($sponsor->nivel);
                        $comision = ($monto * $pocentaje);
                        $comisionAcumulada += $comision;
                        if ($sponsor->nivel <= 5) {
                            $ultimoNivel = $sponsor->nivel;
                            if ($sponsor->status == 1) {
                                
                                $this->preSaveWallet($sponsor->id, $iduser, null, $comision, $concepto, $sponsor->nivel, $sponsor->fullname, $pocentaje);
                            } else {
                                $this->preSaveWallet($user->id, $iduser, null, $comision, $concepto, $sponsor->nivel, $user->fullname, $pocentaje);
                            }   
            
                        }else{
                            //$this->preSaveWallet(2, $iduser, $idcierre, $monto, $concepto, $sponsor->nivel, $sponsor->fullname);
                        }
                    }
                }
                dump('ultimo nivel');
                dump($ultimoNivel);
                $recorrer = 5 - $ultimoNivel;
                dump('recorrer');
                dump($recorrer);
                
                //PAGAMOS LAS COMISIONES RESTANTES AL ADMIN
                if($recorrer > 0){
                    for ($i=0; $i < $recorrer; $i++) { 
                        $ultimoNivel++;
                        $pocentaje = $this->getPorcentage($ultimoNivel);
                        $comision = ($monto * $pocentaje);
                        $comisionAcumulada += $comision;
                        $this->preSaveWallet($sponsor->id, $iduser, null, $comision, $concepto, $ultimoNivel, $sponsor->fullname, $pocentaje);
                    }
                }
                //PAGAMOS 10% al admin
                $pocentaje = $this->getPorcentage(6);
                $comision = ($monto * $pocentaje);
                $comisionAcumulada += $comision;
                $user = User::findOrFail(1);
                $this->preSaveWallet($user->id, $iduser, null, $comision, $concepto, 6, $user->fullname, $pocentaje);
                
                dump('comision acumulada');
                dump($comisionAcumulada);
                //actualizamos la ganancia
                
                $inversion = Inversion::where([
                    //['iduser', '=', $sponsor->id],
                    //['package_id', '=', $package_id],
                    ['orden_id', '=',$orden_id]
                ])->first();
            
                $inversion->ganancia_acumulada += $inversion->ganacia - $comisionAcumulada;
                $inversion->ganacia = 0;
                if($inversion->fecha_vencimiento != null){
                    $inversion->status_por_pagar = 0;
                }
                $inversion->capital-= $comisionAcumulada;
                $inversion->save();
            
            }
        /*} catch (\Throwable $th) {
            Log::error('Wallet - payComision -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }*/
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
    private function preSaveWallet(int $iduser, int $idreferido, int $cierre_id=null,  float $monto, string $concepto, $nivel, $name, $porcentaje=null)
    {
        $data = [
            'iduser' => $iduser,
            'referred_id' => $idreferido,
            'cierre_comision_id' => $cierre_id,
            'monto' => $monto,
            'descripcion' => $concepto,
            'status' => 0,
            'tipo_transaction' => 0,
            'nivel' => $nivel,
            'name' => $name,
            'porcentaje' => $porcentaje
        ];
        dump($data);
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
            1 => 0.20, 2 => 0.05, 3 => 0.02, 4 => 0.01, 5 => 0.02, 6 => 0.10
        ];

        return $nivelPorcentaje[$nivel];
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
            //if ($data['iduser'] != 1) {
                if ($data['tipo_transaction'] == 1) {
                    $wallet = Wallet::create($data);
                    //$saldoAcumulado = ($wallet->getWalletUser->wallet - $data['monto']);
                    $wallet->getWalletUser->update(['wallet' => $saldoAcumulado]);
                    //$wallet->update(['balance' => $saldoAcumulado]);
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
                    $saldoAcumulado = ($wallet->getWalletUser->wallet + $data['monto']);
                    $wallet->getWalletUser->update(['wallet' => $saldoAcumulado]);
                    //$wallet->update(['balance' => $saldoAcumulado]);
                }
            //}
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
            $wallet = Wallet::where([['iduser', '=', $iduser], ['status', '=', 0]])->get()->sum('monto');
            if ($iduser == 1) {
                $wallet = Wallet::where([['status', '=', 0]])->get()->sum('monto');
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
                $Comisiones = Wallet::select(DB::raw('SUM(monto) as Comision'))
                                ->where([
                                    ['status', '<=', 1]
                                ])
                                ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
                                ->orderBy(DB::raw('YEAR(created_at)'), 'ASC')
                                ->orderBy(DB::raw('MONTH(created_at)'), 'ASC')
                                ->take(6)
                                ->get();
            }else{
                $Comisiones = Wallet::select(DB::raw('SUM(monto) as Comision'))
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
