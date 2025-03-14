<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = ['part_number', 'name', 'stock_quantity', 'min_stock_alert'];
}