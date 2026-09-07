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
                
                // Fetch all existing codes matching the category slug or category ID
                $existingCodes = MaterialCode::where('code', 'like', $categoryName . '-%')
                    ->orWhere('product_category_id', $materialCode->product_category_id)
                    ->pluck('code');

                $maxNumber = 0;
                foreach ($existingCodes as $code) {
                    if (preg_match('/-(\d+)$/', $code, $matches)) {
                        $num = (int)$matches[1];
                        if ($num > $maxNumber) {
                            $maxNumber = $num;
                        }
                    }
                }

                $nextNumber = $maxNumber + 1;
                $generatedCode = $categoryName . '-' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);

                // Ensure uniqueness even if non-standard codes exist
                while (MaterialCode::where('code', $generatedCode)->exists()) {
                    $nextNumber++;
                    $generatedCode = $categoryName . '-' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
                }

                $materialCode->code = $generatedCode;
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
