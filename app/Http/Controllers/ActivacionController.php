<?php

namespace App\Http\Controllers;

use App\Models\OrdenPurchases;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class ActivacionController extends Controller
{
    /**
     * Activa los usuario cuando apenas compre
     *
     * @return void
     */
    public function activarUser()
    {
        try {
            $ordenes = OrdenPurchases::where('status', '1')->whereDate('created_at', '>', Carbon::now()->subDays(10))->get();
            foreach ($ordenes as $orden) {
                $orden->getOrdenUser->update(['status' => 1]);
            }
        } catch (\Throwable $th) {
            Log::error('ActivacionController - activarUser -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }
    }
    

    /**
     * Colocar a los usuario en estado de eliminado
     *
     * @return void
     */
    public function deleteUser()
    {
        try {
            User::where('status', '0')
            ->whereDate('created_at', '<', Carbon::now()->subMonth(3))
            ->update(['status' => '5']);
        } catch (\Throwable $th) {
            Log::error('ActivacionController - deleteUser -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }
    }
}
