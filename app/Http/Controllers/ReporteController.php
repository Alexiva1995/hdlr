<?php

namespace App\Http\Controllers;

use App\Models\OrdenPurchases;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ReporteController extends Controller
{
    //

    /**
     * lleva a la vista de informen de pedidos
     *
     * @return void
     */
    public function indexPedidos()
    {
        $ordenes = $this->getOrdenes(0);
        
        return view('reports.perdido', compact('ordenes'));
    }

    /**
     * Permitener las ordenes 
     *
     * @param integer $limite Si limite es igua a 0 es igual a sin limite
     * @return object
     */
    public function getOrdenes($limite): object
    {
        if ($limite == 0) {
            $ordenes = OrdenPurchases::all();
        }else{
            $ordenes = OrdenPurchases::orderBy('id', 'asc')->get()->take($limite);
        }

        foreach ($ordenes as $orden) {
            $orden->name = $orden->getOrdenUser->fullname;
            $orden->grupo = $orden->getGroupOrden->name;
            $orden->paquete = $orden->getPackageOrden->name;
        }

        return $ordenes;
    }

    /**
     * Lleva a la vista de informa de comisiones
     *
     * @return void
     */
    public function indexComision()
    {
        $wallets = Wallet::where([
            ['tipo_transaction', '=', 0],
            ['status', '!=', '3']
        ])->get();

        foreach ($wallets as $wallet) {
            $wallet->name = $wallet->getWalletUser->fullname;
            $wallet->referido = $wallet->getWalletReferred->fullname;
        }

        return view('reports.comision', compact('wallets'));
    }

    /**
     * Permite obtener la informacion para las graficas
     *
     * @return string
     */
    public function graphisDashboard(): string
    {
        $ordenes = $this->getPucharseGraphig();
        $inversiones = $this->getInvertionGraphig();

        $data = [
            'ordenes' => $ordenes,
            'inversiones' => $inversiones
        ];

        return json_encode($data);
    }

    /**
     * Permite obtener las compras de los ultimos 30 dias para la grafica
     *
     * @return object
     */
    private function getPucharseGraphig(): object
    {    
        $ordenes = OrdenPurchases::whereDate('created_at', '>', Carbon::now()->subDays(30))
                                ->where('status', '<', '2')
                                ->selectRaw('SUM(total) as total, DAY(created_at) as dia, MONTH(created_at) as mes')
                                ->orderBy('mes')->orderBy('dia')
                                ->groupByRaw('DAY(created_at)')->get();
        
        
        $label = [];
        $monto = [];
        foreach ($ordenes as $orden) {
            $label[] = 'Mes: '.$orden->mes.' - Dia: '.$orden->dia;
            $monto[] = $orden->total;
        }

        $data = collect([
            'series' => $monto,
            'categorias' => $label
        ]);
        return $data;
    }
    
    /**
     * Permite todos las inversiones compradas
     *
     * @return object
     */
    private function getInvertionGraphig(): object
    {
        $ordenes = OrdenPurchases::where('status', '<', '2')
                                    ->selectRaw('count(id) as cantidad, package_id')
                                    ->groupByRaw('package_id')->get();

        $paquetes = [];
        $total = [];
        foreach ($ordenes as $orden) {
            $paquetes[] = $orden->getPackageOrden->name;
            $total[] = $orden->cantidad;
        }

        $inversiones = collect([
            'label' => $paquetes,
            'cantidad' => $total
        ]);

        return $inversiones;
    }
}
