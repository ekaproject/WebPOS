<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number', 'user_id', 'total_amount',
        'paid_amount', 'status', 'payment_method', 'cashier_terminal',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->invoice_number = 'INV-' . strtoupper(uniqid());
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
}
