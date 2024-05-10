<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Crm extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['apiendpoint','apiusername','apipassword','crmtype'];

    protected $casts = [
        'updated_at'  => 'date:Y-m-d',
    ];

}
