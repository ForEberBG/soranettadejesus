<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoCaja extends Model
{
    protected $table    = 'movimientos_caja';
    protected $fillable = [
        'caja_chica_id','tipo','descripcion',
        'monto','categoria','comprobante','user_id','fecha'
    ];
    protected $casts = ['fecha' => 'date'];

    public function caja()
    {
        return $this->belongsTo(CajaChica::class, 'caja_chica_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
