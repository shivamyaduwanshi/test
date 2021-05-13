<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ProductImage;

class Product extends Model
{
    public function seller(){
        return $this->hasOne('App\Models\User','id','seller_id');
    }
}
