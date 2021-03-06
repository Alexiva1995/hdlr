<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use App\Models\AddSaldo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class RecordController extends Controller
{
    public function index(){
        View::share('titleg', 'Historial Ordenes');
        return view('record.orders');
    }

    public function indexOrders(){
        View::share('titleg', 'Historial Ordenes de Usuarios');
        return view('record.ordersUser');
    }

    public function indexCommissions(){
        $billetera = Wallet::all();

        View::share('titleg', 'Historial Comisiones');
        return view('record.commissions')->with('billetera', $billetera);
    }
    public function indexRequest(){
        $saldo = AddSaldo::all();

        View::share('titleg', 'Historial Pedidos');
        return view('record.request')->with('saldo', $saldo);
    }
}
