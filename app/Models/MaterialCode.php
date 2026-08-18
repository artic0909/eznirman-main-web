<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialCode extends Model
{
    protected $fillable = [
        'code',
        'product_category_id',
        'sub_category',
        'sub_category_two',
        'brand',
        'material_name',
        'created_by',
        'updated_by',
        'creator_type',
        'updater_type'
    ];

    protected static function booted()
    {
        static::creating(function ($materialCode) {
            $category = ProductCategory::find($materialCode->product_category_id);
            if ($category) {
                $categoryName = \Illuminate\Support\Str::upper(\Illuminate\Support\Str::slug($category->name));
                $count = MaterialCode::where('product_category_id', $materialCode->product_category_id)->count() + 1;
                $materialCode->code = $categoryName . '-' . str_pad($count, 2, '0', STR_PAD_LEFT);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function purchases()
    {
        return $this->hasMany(MaterialPurchase::class);
    }
}
