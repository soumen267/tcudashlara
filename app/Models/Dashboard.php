<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dashboard extends Model
{
    use HasFactory;

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function shopify()
    {
        return $this->belongsTo(Shopify::class,'shopify_id');
    }

    public function crm()
    {
        return $this->belongsTo(Crm::class,'crm_id');
    }

    public function smtp()
    {
        return $this->belongsTo(Smtp::class,'smtp_id');
    }
}
