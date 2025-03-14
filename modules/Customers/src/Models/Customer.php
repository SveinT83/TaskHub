<?php

namespace Modules\Customers\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['customer_number', 'name', 'company', 'email'];
}