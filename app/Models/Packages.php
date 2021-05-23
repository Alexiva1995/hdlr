<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Packages extends Model
{
    protected $table = 'packages';

    protected $fillable = [
        'name', 'group_id', 'price', 'description', 'status', 'minimum_deposit', 'expired'
    ];

      /**
     * Permite obtener el grupo al que pertenece
     *
     * @return void
     */
    public function getPackage()
    {
        return $this->belongsTo('App\Models\Packages', 'group_id', 'id');
    }
}
