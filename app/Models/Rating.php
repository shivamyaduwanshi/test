<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ProductImage;

class Rating extends Model
{
    use SoftDeletes;

    protected $table = 'rating_reviews';

    public function user(){
        return $this->hasOne('App\User','id','user_id');
    }

    public function teacher(){
        return $this->hasOne('App\User','id','teacher_id');
    }

}
