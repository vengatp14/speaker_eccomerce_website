<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'productname',
        'description',
        'image',
        'price_structure',
        'legacy_price',
        'price_tiers',
        'category_id',
        'source_file',
    ];

    protected $casts = [
        'price_tiers' => 'array',
        'legacy_price' => 'decimal:2',
    ];

    public function getPrices($type = null)
    {
        $prices = $this->price_tiers ?? [];

        if ($type && isset($prices[$type])) {
            return $prices[$type];
        }

        return $prices;
    }

    public function getLegacyPrice()
    {
        return $this->legacy_price;
    }
    public function category()
{
    return $this->belongsTo(Category::class);
}
}
