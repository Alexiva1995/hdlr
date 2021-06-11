<?php

namespace App\Http\Controllers;

use App\Models\OrdenPurchases;
use App\Models\User;
use Carbon\Carbon;

class ActivacionController extends Controller
{
    /**
     * Activa los usuario cuando apenas compre
     *
     * @return void
     */
    public function activarUser()
    {
        $ordenes = OrdenPurchases::where('status', '1')->whereDate('created_at', '>', Carbon::now()->subDays(10))->get();
        foreach ($ordenes as $orden) {
            $orden->getOrdenUser->update(['status' => 1]);
        }
    }

    /**
     * Colocar a los usuario en estado de eliminado
     *
     * @return void
     */
    public function deleteUser()
    {
        User::where('status', '0')
            ->whereDate('created_at', '>', Carbon::now()->subMonth(3))
            ->update(['status' => 5]);
    }
}
