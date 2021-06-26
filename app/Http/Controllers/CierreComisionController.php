<?php

namespace App\Http\Controllers;

use App\Models\CierreComision;
use App\Models\OrdenPurchases;
use App\Models\Packages;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\WalletController;
use DB;

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
                                    ->selectRaw('SUM(cantidad) as ingreso, group_id, package_id')
                                    // ->whereDate('created_at', Carbon::now()->format('Ymd'))
                                    ->groupBy('package_id', 'group_id')
                                    ->get();
            foreach ($ordenes as $orden) {
                $orden->grupo = $orden->getGroupOrden->name;
                $orden->paquete = $orden->getPackageOrden->name;
                $cierre = CierreComision::where([
                    ['group_id', $orden->group_id], ['package_id', $orden->package_id]
                ])->whereDate('cierre', Carbon::now())->first();
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
            'package_id' => ['required', 'numeric'],
            'saldoFinal_anterior' => ['required']
        ]);
        try {
            if ($validate) {
                $paquete = Packages::find($request->package_id);
                $request['group_id'] = $paquete->group_id;
                $request['cierre'] = Carbon::now();
                /*
                $cierreAnterior = CierreComision::where([['group_id', $request['group_id']], ['package_id', $request->package_id]])->orderBy('id', 'desc')->first();

                if($cierreAnterior){
                    $s_final = $cierreAnterior->s_final;
                }else{
                    $s_final = 0;
                }
                */

                $cierre = CierreComision::create($request->all());
                if($request->saldoFinal_anterior > 0){
                    $ganacia = ($request->saldoFinal_anterior - $cierre->s_inicial);    
                }else{
                    $ganacia = $cierre->s_inicial;
                }

                $comisiones = $this->generateComision($ganacia, $cierre->package_id, $cierre->group_id, $cierre->s_final);
            
                foreach ($comisiones as $comision) {
                   
                    $this->inversionController->updateGanancia($comision['iduser'], $paquete->id, $comision['comision'], $comision['ordenId']);
                    //$wallet = $this->walletController->payComision($comision['comision'], $comision['iduser'], $comision['referido'], $cierre->id);
              
                }
                return redirect()->back()->with('msj-success', 'Cierre realizado y Comisiones pagadas con exito');
            }
        } catch (\Throwable $th) {
            Log::error('CierreComision - store -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }
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
            $total = OrdenPurchases::where([
                ['status', '=', '1'],
                ['package_id', '=', $paquete],
                ['group_id', '=', $grupo]
            ])
            ->select(
                'iduser',
                DB::raw('SUM(cantidad) as total')
            )
            //->selectRaw('SUM(cantidad) as total, iduser')
            // ->whereDate('created_at', Carbon::now()->format('Ymd'))
            ->groupBy('iduser')
            ->first();

            $ordenes = OrdenPurchases::where([
                ['status', '=', '1'],
                ['package_id', '=', $paquete],
                ['group_id', '=', $grupo]
            ])
            ->select(
                'iduser', 'id',
            )
            //->selectRaw('SUM(cantidad) as total, iduser')
            // ->whereDate('created_at', Carbon::now()->format('Ymd'))
            ->get();
           
            $data = collect();

            foreach ($ordenes as $orden) {
                $porcentaje = (($total->total / $saldo_cierre));
                $data->push([
                    'iduser' => $orden->iduser,
                    'comision' => round(($porcentaje * $ganancia), 2),
                    'referido' => $orden->getOrdenUser->fullname,
                    'ordenId' => $orden->id
                ]);
            }
        
            return $data;
        } catch (\Throwable $th) {
            Log::error('CierreComision - generateComision -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }
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
            $paquete = Packages::find($id);
            $ultimoSaldo = CierreComision::where('package_id', $id)->select('s_final', 'cierre', 'created_at')->orderBy('id', 'desc')->first();

            if(isset($ultimoSaldo) && $ultimoSaldo->cierre != null){
                $ingreso = OrdenPurchases::where([
                    ['status', '=', '1'],
                    ['package_id', '=', $id]
                ])->where('created_at', '>=', $ultimoSaldo->created_at)->get()->sum('cantidad');
            }else{
                $ingreso = OrdenPurchases::where([
                    ['status', '=', '1'],
                    ['package_id', '=', $id]
                ])->get()->sum('cantidad');
            }    
            // $ingreso = $paquete->getOrdenPurchase->where('status', '1')->sum('total');
                                        // ->whereDate('created_at', Carbon::now()->format('Ymd'))
                                        // ->sum('total');
            $paquete->save();
            
            $data = collect([
                'paquete' => $paquete->getGroup->name.' - '.$paquete->name,
                'saldo_final' => ($ultimoSaldo != null)? $ultimoSaldo->s_final : 0,
                'ingreso' => $ingreso
            ]);

            return $data->toJson();

        } catch (\Throwable $th) {
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
