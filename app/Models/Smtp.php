<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Smtp extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['name','host','domain','port','email','mailfrom','username','password','emailtemplatepath'];

    protected $casts = [
        'updated_at'  => 'date:Y-m-d',
    ];
}
