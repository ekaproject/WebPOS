<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QcItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'inbound_item_id',
        'good_qty',
        'damaged_qty',
        'checked_at',
        'checked_by',
    ];

    protected $casts = [
        'checked_at' => 'datetime',
    ];

    public function inboundItem()
    {
        return $this->belongsTo(InboundItem::class);
    }

    public function checker()
    {
        return $this->belongsTo(User::class, 'checked_by');
    }
}
