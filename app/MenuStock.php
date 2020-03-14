<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuStock extends Model
{
    protected $table = 'menu_stock';

    protected $fillable = ['restaurant_id', 'menu_id', 'description', 'type', 'quantity', 'weight'];

    public $timestamps = false;

    public static function getMenuStockInfo($id)
    {
        return MenuStock::find($id);
    }

}
