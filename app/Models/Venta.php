<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = [
        'mesa_id',
        'usuario_id',
        'cliente_id',
        'tipo',
        'total',
        'metodo_pago',
        'estado',
        'fecha',
        'estado_sunat',
        'xml_path',
        'pdf_path',
        'cdr_path',
        'tipo_comprobante',
        'serie',
        'correlativo',
        'vuelto',
    ];

    // Relación: una venta pertenece a una mesa (opcional)
    public function mesa()
    {
        return $this->belongsTo(Mesa::class);
    }

    // Relación: una venta pertenece a un usuario
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    // Relación: una venta puede tener un cliente (opcional)
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relación: una venta tiene muchos detalles
    // public function detalles()
    // {
    //     return $this->hasMany(DetalleVenta::class);
    // }

    // Relación: una venta tiene un pedido
    public function pedido()
    {
        return $this->hasOne(Pedido::class);
    }
    // En el modelo Venta.php
    public function detalleVenta()
    {
        return $this->hasMany(DetalleVenta::class, 'venta_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }
}
