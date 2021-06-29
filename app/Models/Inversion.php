<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inversion extends Model
{
    protected $table = 'inversions';

    protected $fillable = [
        'package_id', 'orden_id', 'invertido',
        'ganacia', 'retiro', 'capital', 'progreso',
        'fecha_vencimiento', 'iduser'
    ];

    
     /**
     * Permite obtener al usuario de una Inversion
     *
     * @return void
     */
    public function getInversionesUser()
    {
        return $this->belongsTo('App\Models\User', 'iduser', 'id');
    }

    /**
     * Permite obtener el paquete de una inversion
     *
     * @return void
     */
    public function getPackageOrden()
    {
        return $this->belongsTo('App\Models\Packages', 'package_id');
    }

    /**
     * Permite obtener la orden asociada
     *
     * @return void
     */
    public function getOrdenInversion()
    {
        return $this->belongsTo('App\Models\OrdenPurchases', 'orden_id', 'id');
    }

}
