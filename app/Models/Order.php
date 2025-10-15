<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $table = 'pedidos';
    
    protected $fillable = [
        'producto',
        'cantidad',
        'total',
        'id_usuario'
    ];

    /**
     * Relación con el modelo User
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}
