<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Country;
use App\Models\Timezone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use App\Models\Inversion;
use Carbon\Carbon;
use App\Models\Groups;
use App\Models\Packages;

class UserController extends Controller
{

    // public function index()
    // {
    //     View::share('titleg', 'Usuarios');
    //     return view('users.index');
    // }
    
    /**
     * Vista de la lista de usuarios de la pagina
     *
     * @return void
     */
    public function listUser()
    {

       $user = User::all();

        View::share('titleg', 'Usuarios');

        return view('users.componenteUsers.admin.list-users')
        ->with('user',$user);

    }

    /**
     * Vista para el KYC
     *
     * @return void
     */
    public function kyc()
    {

         View::share('titleg', 'Verificacion KYC');

         return view('users.componenteProfile.kyc');

    }

    /**
     * Funcion para actualizar el KYC
     *
     * @param Request $request
     * @return void
     */
    public function updateProfileKYC(Request $request)
    {

        $user = User::find(Auth::user()->id);

        $fields = [      ];

        $msj = [    ];

        $this->validate($request, $fields, $msj);

        $user->update($request->all());

     if($request->hasFile('dni')){

        $file = $request->file('dni');
        $name = time().$file->getClientOriginalName();
        $ruta = 'dni/'. $user->id .'/'.$name;
        Storage::disk('public')->put($ruta,  \File::get($file));
  
        $user->dni = $name;

     }

     $user->wallet_address = $request->wallet_address;

     $user->save();

     return redirect()->route('kyc')->with('msj-success','Se actualizo tu perfil');

    }

    /**
     * Vista para revisar la informacion del usuario
     *
     * @param [type] $id
     * @return void
     */
    public function showUser($id){

        View::share('titleg', 'Verificacion KYC');

        $user = User::find($id);

        return view('users.componenteUsers.admin.show-user')
        ->with('user', $user);

    }

    /**
     * Vista para editar la informacion del usuario
     *
     * @param [type] $id
     * @return void
     */
    public function editUser($id)
    {

        $user = User::find($id);

        // $timezone = Timezone::orderBy('list_utc','ASC')->get();
        // $countries = Country::orderBy('name','ASC')->get();
 
 
        return view('users.componenteUsers.admin.edit-user')
              ->with('user',$user);
            //   ->with('countries',$countries)
            //   ->with('timezone',$timezone)

    }
    
    /**
     * Funcion para actualizar un usuario
     *
     * @param Request $request
     * @param [type] $id
     * @return void
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);

        $fields = [

         "name" => ['required'],
         "last_name" => ['required'],
         "email" => [
            'required',
            'string',
            'email',
            'max:255',
        ],

        ];

        $msj = [

            'name.required' => 'El nombre es requerido',
            'last_name.required' => 'El telefono es requerido',
            'email.unique' => 'El correo debe ser unico'

        ];

        $this->validate($request, $fields, $msj);

        $fullname = $request->name .' '. $request->last_name;

        $user->update($request->all());
  
        if ($request->hasFile('photoDB')) {
            $file = $request->file('photoDB');

            $nombre = time().$file->getClientOriginalName();

            $ruta = 'photo/'. $user->id .'/'.$nombre;

            Storage::disk('public')->put($ruta,  \File::get($file));
            $user->photoDB = $ruta;
        }

        $user->fullname = $fullname;
        // $user->utc = $request->utc;
        $user->admin = $request->admin;
        $user->status = $request->status;
        // $user->balance = $request->balance;
        // $user->website = $request->website;
        $user->whatsapp = $request->whatsapp;
        $user->address = $request->address;

        $user->save();

        return redirect()->route('users.list-user')->with('msj-success','Se actualizo el perfil de '.$user->fullname.'');
    }

   /**
    * Vista para editar perfil
    *
    * @return void
    */
   public function editProfile()
   {

    //    $timezone = Timezone::orderBy('list_utc','ASC')->get();
    //    $countries = Country::orderBy('name','ASC')->get();

       $user = Auth::user();

       return view('users.profile')
             ->with('user',$user);
            //  ->with('countries',$countries)
            //  ->with('timezone',$timezone)

   }

    /**
     * Funcion para actualizar perfil
     *
     * @param Request $request
     * @return void
     */
    public function updateProfile(Request $request)
    {
        $user = User::find(Auth::user()->id);

        $fields = [

         "name" => ['required'],
         "last_name" => ['required'],
         "email" => [
            'required',
            'string',
            'email',
            'max:255',
        ],

        ];

        $msj = [

            'name.required' => 'El nombre es requerido',
            'last_name.required' => 'El apellido es requerido',
            'email.unique' => 'El correo debe ser unico'

        ];

        $this->validate($request, $fields, $msj);

        $fullname = $request->name .' '. $request->last_name;


        $user->update($request->all());

        if ($request->hasFile('photoDB')) {
            $file = $request->file('photoDB');

            $nombre = time().$file->getClientOriginalName();

            $ruta = 'photo/'. $user->id .'/'.$nombre;

            Storage::disk('public')->put($ruta,  \File::get($file));
            $user->photoDB = $ruta;
        }

        $user->fullname = $fullname;
        $user->whatsapp = $request->whatsapp;

        $user->save();

        return redirect()->route('profile')->with('msj-success','Se actualizo tu perfil');

    }

    public function verifyUser(Request $request, $id)
    {

        $user = User::find($id);

        $user->update($request->all());

        //$user->status = '1';
        $user->verify = '1';

        $user->save();

        return redirect()->route('users.list-user')->with('msj-success','Se Verifico el usuario Exitosamente');

    }

    /**
     * Funcion para eliminar un usuario
     *
     * @param [type] $id
     * @return void
     */
    public function destroyUser($id)
    {
      $user = User::find($id);
      
      $user->delete();
      
      return redirect()->route('users.list-user')->with('msj-success', 'Usuario '.$id.' Eliminado');
    }

    public function updateEstadoReinvertir(Request $request)
    {
        $user = Auth::user();

        if($request->reinvertir == "capital"){
            $user->reinvertir_capital = !$user->reinvertir_capital;
            
            if($user->reinvertir_capital == true){
                $user->reinvertir_capital_package_id = $request->package_id;
                $user->reinvertir_capital_inversion_id = $request->inversion_id;
            }
        }else if($request->reinvertir == "comision"){
            $user->reinvertir_comision = !$user->reinvertir_comision;
        }

        $user->save();
       
        return back()->with('msj-success', 'estado de reinversion actualizado exitosamente');
    }

    public function listPackageInversion()
    {
        //buscamos la inversion mas proxima a vencer
        $inversion = Inversion::where('iduser',Auth::id())->where('status', 1)->whereDate('fecha_vencimiento', '>=', Carbon::now())->orderBy('fecha_vencimiento', 'asc')->first(); 

        $group_id = $inversion->getPackageOrden->group_id;

        $paquetes = Packages::where('group_id', $group_id)->where('status', '1')->whereDate('expired', '>', Carbon::now())->get()->toArray();
      
        return response()->json(['paquete' => $paquetes, 'inversion' => $inversion->id]);
    }

    public function updateEstadoReinvertirCapital()
    {
        $user = Auth::user();

        $user->reinvertir_capital = false;
        $user->save();
        
        return response()->json(true);
    }
}

