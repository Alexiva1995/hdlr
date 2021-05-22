<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    //
    protected $table = 'wallets';

    protected $fillable = [
        'iduser', 'referred_id', 'orden_id', 'liquidation_id', 'debito',
        'credito', 'balance', 'descripcion', 'status', 'tipo_transaction',
        'liquidado'
    ];

    /**
     * Permite obtener la orden de esta comision
     *
     * @return void
     */
    public function getWalletOrden()
    {
        return $this->belongsTo('App\Models\OrdenService', 'orden_id', 'id');
    }

    /**
     * Permite obtener al usuario de una comision
     *
     * @return void
     */
    public function getWalletUser()
    {
        return $this->belongsTo('App\Models\User', 'iduser', 'id');
    }

    /**
     * Permite obtener al referido de una comision
     *
     * @return void
     */
    public function getWalletReferred()
    {
        return $this->belongsTo('App\Models\User', 'referred_id', 'id');
    }
}
