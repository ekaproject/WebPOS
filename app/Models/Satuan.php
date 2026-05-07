<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    protected $table = 'satuan';

    protected $fillable = [
        'nama',
        'singkatan',
    ];

    public function masterProducts()
    {
        return $this->hasMany(MasterProduct::class);
    }

    /**
     * Label lengkap: "Kilogram (kg)"
     */
    public function getLabelAttribute(): string
    {
        return "{$this->nama} ({$this->singkatan})";
    }
}
