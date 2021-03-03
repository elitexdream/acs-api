<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaterialTrack extends Model
{
    public $fillable = ['inventory_material_id', 'start', 'stop', 'in_progress', 'initial_materials'];

    public function inventoryMaterial()
    {
        return $this->belongsTo('App\InventoryMaterial', 'inventory_material_id');
    }
}
