<?php

namespace App\Http\Controllers;

use App\Models\OrdenPurchases;
use App\Models\Wallet;
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
        $ordenes = OrdenPurchases::all();

        
    }

    /**
     * Lleva a la vista de informa de comisiones
     *
     * @return void
     */
    public function indexComision()
    {
        $wallets = Wallet::all();


    }
}
