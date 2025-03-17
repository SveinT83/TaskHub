<?php

namespace Modules\Invoicing\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Projects\Models\Project;
use Modules\Customers\Models\Customer;

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