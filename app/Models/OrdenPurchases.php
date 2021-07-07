<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenPurchases extends Model
{
    protected $table = 'orden_purchases';

    protected $fillable = [
        'iduser', 'group_id', 'package_id', 
        'cantidad', 'total', 'idtransacion',
        'status'
    ];

     /**
     * Permite obtener al usuario de una Compra
     *
     * @return void
     */
    public function getOrdenUser()
    {
        return $this->belongsTo('App\Models\User', 'iduser', 'id');
    }

    /**
     * Permite obtener al usuario de una Compra
     *
     * @return void
     */
    public function getGroupOrden()
    {
        return $this->belongsTo('App\Models\Groups', 'group_id');
    }

    /**
     * Permite obtener al usuario de una Compra
     *
     * @return void
     */
    public function getPackageOrden()
    {
        return $this->belongsTo('App\Models\Packages', 'package_id');
    }

    
    /**
     * Permite obtener la inversion asociada
     *
     * @return void
     */
    public function getInversionOrden()
    {
        return $this->hasOne('App\Models\Inversion', 'orden_id');
    }
}
