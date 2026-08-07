<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'name',
        'asset_category_id',
        'serial_number',
        'cost',
        'purchase_date',
        'warranty_expiry',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(AssetCategory::class, 'asset_category_id');
    }

    public function assignments()
    {
        return $this->hasMany(AssetAssignment::class);
    }

    public function maintenanceLogs()
    {
        return $this->hasMany(AssetMaintenanceLog::class);
    }
}
