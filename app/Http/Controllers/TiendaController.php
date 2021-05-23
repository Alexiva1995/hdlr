<?php

namespace App\Http\Controllers;

use App\Models\Groups;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TiendaController extends Controller
{

    
    /**
     * Lleva a la vista de la tienda
     *
     * @return void
     */ 
    public function index()
    {
        try {
            // title
            View::share('titleg', 'Tienda - Grupos');
            $categories = Groups::all()->where('status', 1);

            return view('shop.index', compact('categories'));
        } catch (\Throwable $th) {
            Log::error('Tienda - Index -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }
    }

    /**
     * Lleva a la vista de productos de un paquete en especificio
     *
     * @param integer $idgroup
     * @return void
     */
    public function products($idgroup)
    {
        try {
            // title
            View::share('titleg', 'Tienda - Productos');
            $category = Groups::find($idgroup);
            $services = $category->getPackage;

            return view('shop.products', compact('services'));
        } catch (\Throwable $th) {
            Log::error('Tienda - products -> Error: '.$th);
            abort(403, "Ocurrio un error, contacte con el administrador");
        }
    }
}
