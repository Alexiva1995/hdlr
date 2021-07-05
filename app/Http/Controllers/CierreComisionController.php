<?php

namespace App\Http\Controllers;

use App\Models\CierreComision;
use App\Models\OrdenPurchases;
use App\Models\Packages;
use App\Models\Groups;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\WalletController;
use DB;
use App\Models\Inversion;

class CierreComisionController extends Controller
{
    /**
     * Variable Global del WalletController
     *
     * @var WalletController
     */
    public $walletController;
    public $inversionController;

    public function __construct()
    {
        $this->walletController = new WalletController();
        $this->inversionController = new InversionController;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            // title
            View::share('titleg', 'Cierre de Comisiones');

            $ordenes = OrdenPurchases::where('status', '=', '1')
                                    ->selectRaw('SUM(cantidad) as ingreso, group_id')
                                    // ->whereDate('created_at', Carbon::now()->format('Ymd'))
                                    ->groupBy('group_id')
                                    ->get();
            foreach ($ordenes as $orden) {
                $orden->grupo = $orden->getGroupOrden->name;
                $orden->description = $orden->getGroupOrden->description;
                //$orden->paquete = $orden->getPackageOrden->name;
                $cierre = CierreComision::where([
                    ['group_id', $orden->group_id]
                ])->whereDate('cierre', Carbon::now())->orderBy('id', 'desc')->first();
                $orden->cerrada = ($cierre != null) ? 1 : 0;
                $orden->fecha_cierre = ($cierre != null) ? $cierre->cierre: '';
            }

            return view('accounting.index', compact('ordenes'));
        } catch (\Throwable $th) {
            Log::error('CierreComision - index -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            's_inicial' => ['required', 'numeric'],
            's_ingreso' => ['required', 'numeric'],
            's_final' => ['required', 'numeric', 'min:1'],
            'group_id' => ['required', 'numeric'],
            'saldoFinal_anterior' => ['required']
        ]);

        //try {
            if ($validate) {
                $paquete = Groups::find($request->group_id);
                $request['cierre'] = Carbon::now();

                $cierre = CierreComision::create($request->all());
          
                $ganacia = ($cierre->s_inicial - $request->saldoFinal_anterior);    
                
                //dump($ganacia);
                //dump($cierre->s_final);
                if($cierre->s_inicial < 1){
                    $comisiones = $this->generateComision($ganacia, $cierre->package_id, $cierre->group_id, $cierre->s_final);
             
                    foreach ($comisiones as $comision) {
                       
                        $this->inversionController->updateGanancia($comision['iduser'], $paquete->id, $comision['comision'], $comision['ordenId'], $comision['porcentaje']);
                        //$wallet = $this->walletController->payComision($comision['comision'], $comision['iduser'], $comision['referido'], $cierre->id);
                  
                    }
                }else{
                    //REPARTICION DE GANANCIA
                    //$ganancia = ($cierre->s_inicial - $request->saldoFinal_anterior);
                     
                    $this->repartirGanancia($cierre->group_id, $ganacia);

                     $comisiones = $this->generateComision($ganacia, $cierre->package_id, $cierre->group_id, $cierre->s_final);
             
                     foreach ($comisiones as $comision) {
                       
                        $this->inversionController->updatePorcentaje($comision['iduser'], $paquete->id, $comision['comision'], $comision['ordenId'], $comision['porcentaje']);
                        //$wallet = $this->walletController->payComision($comision['comision'], $comision['iduser'], $comision['referido'], $cierre->id);
                  
                    }
                }

                return redirect()->back()->with('msj-success', 'Cierre realizado y Comisiones pagadas con exito');
            }/*
        } catch (\Throwable $th) {
            Log::error('CierreComision - store -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }*/
    }

    /**
     * Permite general el pago de las comisiones 
     *
     * @param float $ganancia
     * @param integer $paquete
     * @param integer $grupo
     * @param float $saldo_cierre
     * @return object
     */
    private function generateComision($ganancia, $paquete, $grupo, $saldo_cierre): object
    {
        try {
           
            
            $ordenes = Inversion::whereHas('getPackageOrden', function($package)use ($grupo){
                $package->where('group_id', $grupo);
            })
            ->select(
                'iduser', 'id','capital', 'orden_id'
            )
            ->get();

            //return $ordenes;
            $data = collect();
            
            foreach ($ordenes as $orden) {
                $porcentaje = ($orden->capital / $saldo_cierre);
                $data->push([
                    'porcentaje' => $porcentaje,
                    'iduser' => $orden->iduser,
                    'comision' => round(($porcentaje * $ganancia), 2),
                    'referido' => $orden->getInversionesUser->fullname,
                    'ordenId' => $orden->getOrdenInversion->id
                ]);
            }
        
            return $data;
        } catch (\Throwable $th) {
            Log::error('CierreComision - generateComision -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }
    }

    public function repartirGanancia($grupo, $ganancia)
    {
        try {
            $inversiones = Inversion::whereHas('getPackageOrden', function($paquete)use ($grupo){
                $paquete->where('group_id', $grupo);
            })->get();
           
            //return $ordenes;
            $comisiones = collect();
            
            foreach ($inversiones as $inversion) {

                if($inversion->porcentaje_fondo != null && $inversion->porcentaje_fondo > 0.00){
            
                    $comisiones->push([
                        'porcentaje' => $inversion->porcentaje_fondo,
                        'iduser' => $inversion->iduser,
                        'comision' => ($inversion->porcentaje_fondo * $ganancia),
                        'referido' => $inversion->getInversionesUser->fullname,
                        'ordenId' => $inversion->getOrdenInversion->id
                    ]);
                }
            }

            foreach ($comisiones as $comision) {
                           
                $this->inversionController->updateGanancia($comision['iduser'], null, $comision['comision'], $comision['ordenId'], $comision['porcentaje']);
          
            }
            return $comisiones;
        } catch (\Throwable $th) {
            Log::error('CierreComision - repartirGanancia -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }
    }

    public function pagarUtilidadFinDeMes(Request $request)
    {
        $inversiones = Inversion::where('status_por_pagar', '1')->get();

        $comisiones = collect();
            
        foreach ($inversiones as $inversion) {

            //if($inversion->porcentaje_fondo != null && $inversion->porcentaje_fondo > 0.00){
        
                $comisiones->push([
                    'porcentaje' => $inversion->porcentaje_fondo,
                    'iduser' => $inversion->iduser,
                    'comision' => $inversion->ganacia,
                    'referido' => $inversion->getInversionesUser->email,
                    'ordenId' => $inversion->getOrdenInversion->id,
                    'inversion_id' => $inversion->id,
                    'orden_id' => $inversion->orden_id,
                    'package_id' => $inversion->package_id 
                ]);
            //}
        }
        dump($comisiones);
        foreach($comisiones as $comision){
            //dump($comision);
            dump('referido');
            dump( $comision['referido']);
            dump('monto');
            dump($comision['comision']);
            if($comision['comision'] > 0){
                $wallet = $this->walletController->payComision($comision['comision'], $comision['iduser'], $comision['referido'], $comision['inversion_id'], $comision['orden_id'], $comision['package_id']);
            }
            //dump($wallet);
        }

        //Recalculamos los porcentajes
        $capitalTotal = Inversion::where('status_por_pagar', '1')->sum('capital');

        $inversiones = Inversion::where('status_por_pagar', '1')->get();

        foreach ($inversiones as $inversion) {
            $inversion->porcentaje_fondo = $inversion->capital / $capitalTotal;
            $inversion->save();
        }

        dd("listo");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $group = Groups::find($id);
            $ultimoSaldo = CierreComision::where('group_id', $id)->select('s_final', 'cierre', 'created_at')->orderBy('id', 'desc')->first();

            if(isset($ultimoSaldo) && $ultimoSaldo->cierre != null){
                $ingreso = OrdenPurchases::where([
                    ['status', '=', '1'],
                    ['group_id', '=', $id]
                ])->where('created_at', '>=', $ultimoSaldo->created_at)->get()->sum('cantidad');
            }else{
                $ingreso = OrdenPurchases::where([
                    ['status', '=', '1'],
                    ['group_id', '=', $id]
                ])->get()->sum('cantidad');
            }    
            // $ingreso = $paquete->getOrdenPurchase->where('status', '1')->sum('total');
                                        // ->whereDate('created_at', Carbon::now()->format('Ymd'))
                                        // ->sum('total');
            $group->save();
            
            $data = collect([
                'group' => $group->name,
                'saldo_final' => ($ultimoSaldo != null)? $ultimoSaldo->s_final : 0,
                'ingreso' => $ingreso
            ]);

            return $data->toJson();

        }catch (\Throwable $th) {
            Log::error('CierreComision - show -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
