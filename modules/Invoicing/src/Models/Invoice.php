<?php

namespace Modules\Invoicing\src\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Projects\src\Models\Project;
use Modules\Customers\src\Models\Customer;

class Invoice extends Model
{
    protected $fillable = ['project_id', 'customer_id', 'amount', 'status'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}