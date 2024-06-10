<?php

namespace App\Models;

use App\Models\Shopify;
use App\Models\Dashboard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['dashboard_id'];
    
    public function dashb()
    {
        return $this->belongsTo(Dashboard::class, 'dashboard_id');
    }

    public function shopify()
    {
        return $this->belongsTo(Shopify::class, 'shopify_id');
    }
}
