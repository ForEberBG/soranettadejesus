<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngredientePlato extends Model
{
    use HasFactory;
    protected $table = 'ingrediente_plato';
    protected $fillable = ['plato_id', 'ingrediente_id', 'cantidad_usada'];

    public function plato()
    {
        return $this->belongsTo(Plato::class);
    }

    public function ingrediente()
    {
        return $this->belongsTo(Ingrediente::class);
    }
}
