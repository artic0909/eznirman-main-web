<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $fillable = ['name', 'status'];

    public function materialCodes()
    {
        return $this->hasMany(MaterialCode::class);
    }
}
