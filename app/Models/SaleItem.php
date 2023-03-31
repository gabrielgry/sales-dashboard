<?php

namespace App\Models;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = ['product', 'price'];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }
}
