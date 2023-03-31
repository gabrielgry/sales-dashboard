<?php

namespace App\Models;

use App\Models\User;
use App\Models\SaleItem;
use App\Models\Installment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->HasMany(SaleItem::class);
    }

    public function installments(): HasMany
    {
        return $this->hasMany(Installment::class);
    }
}
