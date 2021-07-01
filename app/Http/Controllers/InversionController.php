<?php

namespace App\Http\Controllers;

use App\Models\Inversion;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InversionController extends Controller
{
    /**
     * Lleva a a la vista de las inversiones
     *
     * @param [type] $tipo
     * @return void
     */
    public function index($tipo)
    {
       try {
           $this->checkStatus();
            if ($tipo == '') {
                $inversiones = Inversion::all();
            } else {
                if (Auth::id() == 1) {
                    $inversiones = Inversion::where('status', '=', $tipo)->get();
                }else{
                    $inversiones = Inversion::where([['status', '=', $tipo], ['iduser', '=',Auth::id()]])->get();
                }
            }

            foreach ($inversiones as $inversion) {
                $inversion->correo = $inversion->getInversionesUser->email;
            }
            
            return view('inversiones.index', compact('inversiones'));
        } catch (\Throwable $th) {
            Log::error('InversionController - index -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }
    }

    /**
     * Permite guardar las nuevas inversiones generadas
     *
     * @param integer $paquete - ID del Paquete Comprado
     * @param integer $orden - ID de la compra Comprada
     * @param float $invertido - Monto Total Invertido
     * @param string $vencimiento - Fecha de Vencimiento del paquete
     * @param integer $iduser - ID del usuario 
     * @return void
     */
    public function saveInversion(int $paquete, int $orden, float $invertido, string $vencimiento, int $iduser)
    {
        try {
            $check = Inversion::where([
                ['iduser', '=', $iduser],
                ['package_id', '=', $paquete],
                ['orden_id', '=', $orden],
            ])->first();
            if ($check == null) {
                $data = [
                    'iduser' => $iduser,
                    'package_id' => $paquete,
                    'orden_id' => $orden,
                    'invertido' => $invertido,
                    'ganacia' => 0,
                    'retiro' => 0,
                    'capital' => $invertido,
                    'progreso' => 0,
                    'fecha_vencimiento' => $vencimiento,
                ];
                Inversion::create($data);
            }
        } catch (\Throwable $th) {
            Log::error('InversionController - saveInversion -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }
    }

    /**
     * Permite Verificar si una inversion esta terminada
     *
     * @return void
     */
    public function checkStatus()
    {
        Inversion::whereDate('fecha_vencimiento', '<', Carbon::now())->update(['status' => 2]);
    }

    public function updateGanancia(int $iduser, int $paquete, float $ganacia, int $ordenId=0, $porcentaje=null)
    {
        try {
            if($ordenId != 0){
                $inversion = Inversion::where([
                    ['iduser', '=', $iduser],
                    ['package_id', '=', $paquete],
                    ['status', '=', 1],
                    ['orden_id', '=',$ordenId]
                ])->first();
            }else{
                $inversion = Inversion::where([
                    ['iduser', '=', $iduser],
                    ['package_id', '=', $paquete],
                    ['status', '=', 1]
                ])->first();
            }
        
            if ($inversion != null) {
             
                $capital = ($inversion->capital + $ganacia);
                $inversion->ganacia = ($inversion->ganacia + $ganacia);
                $inversion->capital = $capital;      
                $inversion->porcentaje_fondo = $porcentaje;
          
                $inversion->save();
            }
        } catch (\Throwable $th) {
            Log::error('InversionController - updateGanancia -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }
    }

     public function updatePorcentaje(int $iduser, int $paquete, float $ganacia, int $ordenId=0, $porcentaje=null)
    {
        try {
            if($ordenId != 0){
                $inversion = Inversion::where([
                    ['iduser', '=', $iduser],
                    ['package_id', '=', $paquete],
                    ['status', '=', 1],
                    ['orden_id', '=',$ordenId]
                ])->first();
            }else{
                $inversion = Inversion::where([
                    ['iduser', '=', $iduser],
                    ['package_id', '=', $paquete],
                    ['status', '=', 1]
                ])->first();
            }
        
            if ($inversion != null) {
                
                $inversion->porcentaje_fondo = $porcentaje;
          
                $inversion->save();
            }
        } catch (\Throwable $th) {
            Log::error('InversionController - updateGanancia -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }
    }
}
