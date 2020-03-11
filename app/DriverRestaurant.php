<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DriverRestaurant extends Model
{
    protected $table = 'driver_restaurant';

    protected $fillable = ['user_id', 'restaurant_id', 'status'];

    public $timestamps = false;

}
