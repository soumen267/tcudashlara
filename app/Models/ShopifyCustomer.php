<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopifyCustomer extends Model
{
    use HasFactory;

    public function crmOrders()
    {
        return $this->hasMany(CrmOrder::class);
    }
}
