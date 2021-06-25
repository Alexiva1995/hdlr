<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable

{
    use Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'last_name', 'email', 'password', 'whatsapp',
        'fullname', 'referred_id', 'binary_id', 'admin', 'balance', 'status',
        'wallet', 'address', 'binary_side', 'binary_side_register', 'dni', 'photoDB', 'wallet_address'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ]; 



    /**
     * Permite obtener todas las ordenes de compra de saldo realizadas
     *
     * @return void
     */
    public function getWallet()
    {
        return $this->hasMany('App\Models\Wallet', 'iduser');
    }

    /**
     * Permite obtener todas la liquidaciones que tengo
     *
     * @return void
     */
    public function getLiquidate()
    {
        return $this->hasMany('App\Models\Liquidaction', 'iduser');
    }

    /**
     * Permite obtener las ordenes de servicio asociada a una categoria
     *
     * @return void 
     */
    public function getUserOrden()
    {
        return $this->hasMany('App\Models\OrdenPurchases', 'iduser');
    }

    /**
     * Permite obtener las Inversiones asociadas a un usuario
     *
     * @return void 
     */
    public function getUserInversiones()
    {
        return $this->hasMany('App\Models\Inversion', 'iduser');
    }

    public function montoInvertido()
    {
        $monto = 0;
        foreach($this->getUserInversiones as $inversion){
            if($inversion->status == 1){
                $monto+= $inversion->invertido;
            }
        }

        return number_format($monto,2);
    }
}
