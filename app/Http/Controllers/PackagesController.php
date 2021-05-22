<?php

namespace App\Http\Controllers;

use App\Models\Groups;
use App\Models\Packages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class PackagesController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         try {
             // title
             View::share('titleg', 'Paquetes');

             $categories = Groups::all()->where('status', 1);
             if (!empty(request()->category)) {
                 $services = Packages::all()->where('group_id', request()->category);

                 $category = Groups::find(request()->category);
                 $name_category = $category->name;
             }

         return view('manager_services.services.index', compact('categories', 'services','name_category'));
         } catch (\Throwable $th) {
             dd($th);
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
            'name' => ['required'],
            'group_id' => ['required'],
            'minimum_deposit' => ['required', 'numeric'],
            'expired' => ['required', 'date'],
            'price' => ['required', 'numeric'],
        ]);

        try {
            if ($validate) {
                Packages::create($request->all());
                $route = route('package.index').'?category='.$request->group_id;
                return redirect($route)->with('msj-success', 'Nuevo Servicio Creado');
            }
        } catch (\Throwable $th) {
            dd($th);
        }

    }

        /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $service = Packages::find($id);
            $category = $service->group_id;
            $service->delete();
            $route = route('package.index').'?category='.$category;
            return redirect($route)->with('msj-success', 'Servicio '.$id.' Eliminado');
        } catch (\Throwable $th) {
            dd($th);
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
            $service = Packages::find($id);
            return $service->description;
        } catch (\Throwable $th) {
            dd($th);
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
        try {
            $category = Packages::find($id);
            return json_encode($category);
        } catch (\Throwable $th) {
            dd($th);
        }
    }


    public function update(Request $request, $id)
    {

             $validate = $request->validate([
                'name' => ['required'],
                'group_id' => ['required'],
                'minimum_deposit' => ['required', 'numeric'],
                'expired' => ['required', 'date'],
                'price' => ['required', 'numeric'],
             ]);

         try {
             if ($validate) {
                 $service = Packages::find($id);
                 $service->name = $request->name;
                 $service->group_id = $request->group_id;
                 $service->minimum_deposit = $request->minimum_deposit;
                 $service->expired = $request->expired;
                 $service->price = $request->price;
                 $service->status = $request->status;
                 $service->description = $request->description;
                 $service->save();
                 $route = route('package.index').'?category='.$request->group_id;
                 return redirect($route)->with('msj-success', 'Servicio '.$id.' Actualizado ');
             }
         } catch (\Throwable $th) {
             dd($th);
         }
    }

}
