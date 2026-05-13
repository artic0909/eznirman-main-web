<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialConsume extends Model
{
    protected $fillable = [
        'material_purchase_id', 
        'consume_date', 
        'quantity_current', 
        'used_quantity', 
        'available_quantity', 
        'unit', 
        'use_now', 
        'from_site_id', 
        'to_site_id', 
        'note'
    ];

    public function purchase()
    {
        return $this->belongsTo(MaterialPurchase::class, 'material_purchase_id');
    }

    public function fromSite()
    {
        return $this->belongsTo(WorkingSite::class, 'from_site_id');
    }

    public function toSite()
    {
        return $this->belongsTo(WorkingSite::class, 'to_site_id');
    }

    /**
     * Get stock available at a specific site for a purchase ref
     */
    public static function getSiteStock($purchaseId, $siteId)
    {
        $purchase = MaterialPurchase::find($purchaseId);
        if (!$purchase) return 0;

        $initial = ($purchase->working_site_id == $siteId) ? $purchase->quantity : 0;
        
        $transfersIn = self::where('material_purchase_id', $purchaseId)
            ->where('to_site_id', $siteId)
            ->where('use_now', 1)
            ->sum('used_quantity');

        $transfersOut = self::where('material_purchase_id', $purchaseId)
            ->where('from_site_id', $siteId)
            ->where('use_now', 1)
            ->sum('used_quantity');

        $consumptions = self::where('material_purchase_id', $purchaseId)
            ->where('from_site_id', $siteId)
            ->where('use_now', 0)
            ->sum('used_quantity');

        return round(($initial + $transfersIn) - ($transfersOut + $consumptions), 2);
    }

    /**
     * Get all sites that currently hold stock for a purchase
     */
    public static function getStockLocations($purchaseId)
    {
        $purchase = MaterialPurchase::find($purchaseId);
        if (!$purchase) return [];

        $siteIds = self::where('material_purchase_id', $purchaseId)
            ->pluck('from_site_id')
            ->merge(self::where('material_purchase_id', $purchaseId)->pluck('to_site_id'))
            ->push($purchase->working_site_id)
            ->filter()
            ->unique();

        $locations = [];
        foreach ($siteIds as $siteId) {
            $balance = self::getSiteStock($purchaseId, $siteId);
            if ($balance > 0) {
                $site = WorkingSite::find($siteId);
                if ($site) {
                    $locations[] = [
                        'id' => $site->id,
                        'name' => $site->site_name,
                        'code' => $site->site_code,
                        'balance' => $balance
                    ];
                }
            }
        }
        return $locations;
    }
}
