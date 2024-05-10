<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shopify extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['shopifyapikey','shopifyapipassword','shopifyshopname','shopifydomainname','storeurl'];

    protected $casts = [
        'updated_at'  => 'date:Y-m-d',
    ];

}
