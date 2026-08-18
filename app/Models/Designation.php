<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $fillable = ['name', 'status', 'created_by', 'updated_by', 'creator_type', 'updater_type'];
}
