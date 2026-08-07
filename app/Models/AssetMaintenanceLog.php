<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetMaintenanceLog extends Model
{
    protected $fillable = [
        'asset_id',
        'repair_date',
        'cost',
        'vendor',
        'notes',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
