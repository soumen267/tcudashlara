<?php

namespace App\Models;

use App\Models\ShopifyCustomer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CrmOrder extends Model
{
    use HasFactory;


    public function shopifyCustomers()
    {
        return $this->belongsTo(ShopifyCustomer::class, 'shopify_customers_id');
    }
}
