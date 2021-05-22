<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Groups;
use Illuminate\Support\Facades\View;

class GroupsController extends Controller
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
            View::share('titleg', 'Grupos');

            $categories = Groups::all()->except('created_at', 'updated_at');

            return view('manager_services.categories.index', compact('categories'));
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
            'name' => ['required', 'unique:groups'],
        ]);
        try {
            if ($validate) {

                Groups::create($request->all());
                
                return redirect()->back()->with('msj-success', 'Nuevo Grupo Creada');
            }
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
        //
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
            $category = Groups::find($id)->only('name', 'description', 'id', 'status');
            return json_encode($category);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = Groups::find($id);
        if ($category->name != $request->name) {
            $validate = $request->validate([
                'name' => ['required', 'unique:groups'],
            ]);
        }else{
            $validate = true;
        }
        
        try {
            if ($validate) {

                $category->name = $request->name;
                $category->status = $request->status;
                $category->description = $request->description;
                $category->save();                
                
                return redirect()->back()->with('msj-success', 'Grupo '.$id.' Actualizada ');
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
            Groups::find($id)->delete();

            return redirect()->back()->with('msj-success', 'Grupo '.$id.' Eliminada');
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
