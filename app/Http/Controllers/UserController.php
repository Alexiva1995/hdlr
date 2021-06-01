<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

use App\Models\User;
use App\Models\Country;
use App\Models\Timezone;

class UserController extends Controller
{
    // public function index()
    // {
    //     View::share('titleg', 'Usuarios');
    //     return view('users.index');
    // }


     public function kyc()
     {
         View::share('titleg', 'Verificacion KYC');
         return view('users.componenteProfile.kyc');
     }

     public function showUser($id){

        View::share('titleg', 'Verificacion KYC');

        $user = User::find($id);

        return view('users.componenteUsers.admin.show-user')
        ->with('user', $user);
    }


    public function listUser()
    {
       $user = User::all();

        View::share('titleg', 'Usuarios');
        return view('users.componenteUsers.admin.list-users')
        ->with('user',$user);
    }

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
            'email.unique' => 'El correo debe ser unico',
        ];

        $this->validate($request, $fields, $msj);

        $fullname = $request->name .' '. $request->last_name;

        // foto
        $user->update($request->all());
  
        if ($request->hasFile('photoDB')) {
            $file = $request->file('photoDB');
            $name = $user->id.'_'.$file->getClientOriginalName();
            $file->move(public_path('storage') . '/photo', $name);
            $user->photoDB = $name;
    
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

    // public function registerMediaConversions(Media $media = null)
    // {
    //     $this->addMediaConversion('photo');
    // }

    public function store(Request $request)
    {

    }

    // permite eliminar una orden
    
    public function destroyUser($id)
    {
      $user = User::find($id);
      
      $user->delete();
      
      return redirect()->route('users.list-user')->with('msj-success', 'Usuario '.$id.' Eliminado');
    }





   // vista de editar perfil

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


    public function updateProfile(Request $request)
    {
        $user = Auth::user();

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
            'email.unique' => 'El correo debe ser unico',
        ];

        $this->validate($request, $fields, $msj);

        $fullname = $request->name .' '. $request->last_name;

        // foto
        $user->update($request->all());

     if ($request->hasFile('photoDB')) {
        $file = $request->file('photoDB');
        $name = $user->id.'_'.$file->getClientOriginalName();
        $file->move(public_path('storage') . '/photo', $name);
        $user->photoDB = $name;

     }
        $user->fullname = $fullname;
        $user->whatsapp = $request->whatsapp;
        $user->save();

        
        return redirect()->route('profile')->with('msj-success','Se actualizo tu perfil');

    }

    public function updateProfileKYC(Request $request)
    {
        $user = Auth::user();

        $fields = [

        ];

        $msj = [

        ];

        $this->validate($request, $fields, $msj);

        $user->update($request->all());

     if($request->hasFile('dni')){

        $file = $request->file('dni');
        $name = $user->id.'_'.$file->getClientOriginalName();
        $file->move(public_path('storage') . '/dni', $name);
        $user->dni = $name;
     }
     $user->wallet_address = $request->wallet_address;

     $user->save();

        
        return redirect()->route('kyc')->with('msj-success','Se actualizo tu perfil');

    }
    

}

