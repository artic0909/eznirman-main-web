<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machinary extends Model
{
    use HasFactory;

    protected $table = 'machinaries';

    protected $fillable = [
        'machine_category_id', 
        'name', 
        'machine_code', 
        'image', 
        'entry_date', 
        'condition', 
        'status',
        'created_by',
        'updated_by',
        'creator_type',
        'updater_type'
    ];

    public function category()
    {
        return $this->belongsTo(MachineCategory::class, 'machine_category_id');
    }

    public function transfers()
    {
        return $this->hasMany(Transfer::class, 'machinery_id');
    }
}
