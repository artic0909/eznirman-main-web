<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status', 'created_by', 'updated_by', 'creator_type', 'updater_type'];

    public function machineries()
    {
        return $this->hasMany(Machinary::class, 'machine_category_id');
    }
}
