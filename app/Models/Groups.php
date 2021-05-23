<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{

    protected $table = 'groups';

    protected $fillable = [
        'name', 'status', 'description', 'img'
    ];


    /**
     * Permite obtener todos los paquetes de un grupo
     *
     * @return void
     */
    public function getPackage()
    {
        return $this->hasMany('App\Models\Packages', 'group_id');
    }
}
