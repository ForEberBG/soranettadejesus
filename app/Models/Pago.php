<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $fillable = ['venta_id', 'metodo', 'monto'];

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }
}
