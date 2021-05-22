<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

use function GuzzleHttp\json_decode;

class PackageController extends Controller
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
             View::share('titleg', 'Servicios');

             $categories = Category::all()->where('status', 1);
             if (!empty(request()->category)) {
                 $services = Service::all()->where('categories_id', request()->category);
                 foreach ($services as $service) {
                     $service->input_adicionales = null;
                     if ($service->input_adicionales != null || $service->input_adicionales != '') {
                         $service->input_adicionales = json_decode($service->input_adicionales);
                     }
                 }
                 $category = Category::find(request()->category);
                 $name_category = $category->name;
             }
             $types_services = $this->getServiceType();
             $api_providers = $this->getAPIProvider();

         return view('manager_services.services.index', compact('categories', 'services', 'types_services', 'api_providers','name_category'));
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
            'package_name' => ['required'],
            'categories_id' => ['required'],
            'minimum_amount' => ['required'],
            'maximum_amount' => ['required'],
            'price' => ['required'],
            'type_services' => ['required'],
            'type' => ['required'],
            'input_adicionales' => ['required']
        ]);

        try {
            if ($validate) {
                $request['input_adicionales'] = json_encode($request->input_adicionales);
                Service::create($request->all());
                $route = route('services.index').'?category='.$request->categories_id;
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
            $service = Service::find($id);
            $category = $service->categories_id;
            $service->delete();
            $route = route('services.index').'?category='.$category;
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
            $service = Service::find($id);
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
            $category = Service::find($id);
            return json_encode($category);
        } catch (\Throwable $th) {
            dd($th);
        }
    }


    public function update(Request $request, $id)
    {

             $validate = $request->validate([
            'package_name' => ['required'],
            'categories_id' => ['required'],
            'minimum_amount' => ['required'],
            'maximum_amount' => ['required'],
            'price' => ['required'],
            'type_services' => ['required'],
            'type' => ['required']

             ]);

         try {
             if ($validate) {
                 $service = Service::find($id);
                 $service->package_name = $request->package_name;
                 $service->categories_id = $request->categories_id;
                 $service->minimum_amount = $request->minimum_amount;
                 $service->maximum_amount = $request->maximum_amount;
                 $service->price = $request->price;
                 $service->status = $request->status;
                 $request->input_adicionales = json_encode($request->input_adicionales);
                 $service->type_services = $request->type_services;
                 $service->drip_feed = (!empty($request->drip_feed)) ? $request->drip_feed : $service->drip_feed;
                 $service->type = $request->type;
                 $service->api_provide_name = $request->api_provide_name;
                 $service->api_service_id = $request->api_service_id;
                 $service->description = $request->description;
                 $service->save();
                 $route = route('services.index').'?category='.$request->categories_id;
                 return redirect($route)->with('msj-success', 'Servicio '.$id.' Actualizado ');
             }
         } catch (\Throwable $th) {
             dd($th);
         }
    }


    // permite ver la lista de ordenes

    public function indexAdmin()
    {
        $orden = OrdenService::whereIn('categories_id', ['3','4','5','6','7','8','9','14','16','17','18','19'])->get();

        View::share('titleg', 'Historial de Ordenes');

        return view('record.componenteRecord.admin.orders-admin')
        ->with('orden', $orden);
    }

    // permite editar la orden

    public function editAdmin($id)
    {

        $orden = OrdenService::find($id);
        return view('record.componenteRecord.admin.edit-order-admin')
        ->with('orden', $orden);
        // try {
        //     $service = Service::find($id);
        //     $service->input_adicionales = json_decode($service->input_adicionales);
        //     return json_encode($service);
        // } catch (\Throwable $th) {
        //     dd($th);
        // }
    }

    // permite actualizar la orden

    public function updateAdmin(Request $request, $id)
    {

        $orden = OrdenService::find($id);

        $fields = [
            'status' => ['required'],
            'count_start' => ['required'],
            'count_end' => ['required']
        ];
        
        $msj = [
            'status.required' => 'Es requerido el Estatus de la Orden',
            'count_start.required' => 'Es requerido los seguidores actuales',
            'count_end.required' => 'Es requerido los seguidores faltantes',
        ];
        
        $this->validate($request, $fields, $msj);

        $orden->update($request->all());
        $orden->save();
        
        return redirect()->route('record_order.index-admin')->with('msj-success', 'Orden '.$id.' Actualizado');

    }

    // permite ver la orden

    public function showAdmin($id)
    {
       $orden = OrdenService::find($id);
       return view('record.componenteRecord.admin.show-order-admin')
       ->with('orden', $orden);
    }

    // permite eliminar una orden
    
    public function destroyAdmin($id)
    {
      $orden = OrdenService::find($id);
    
      $orden->delete();
    
      return redirect()->route('record_order.index-admin')->with('msj-success', 'Orden '.$id.' Eliminada');
    }
   
   

}
