<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialCode extends Model
{
    protected $fillable = ['code', 'product_category_id', 'material_name'];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function purchases()
    {
        return $this->hasMany(MaterialPurchase::class);
    }
}
