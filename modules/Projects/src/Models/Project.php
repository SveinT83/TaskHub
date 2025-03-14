<?php

namespace Modules\Projects\src\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Customers\src\Models\Customer;

class Project extends Model
{
    protected $fillable = ['project_number', 'customer_id', 'status'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}