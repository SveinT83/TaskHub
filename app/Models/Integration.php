<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Integrations\IntegrationCredential;

class Integration extends Model
{
    use HasFactory;

    protected $table = 'integrations';

    protected $fillable = [
        'name',
        'active',
        'icon',
        'created_at',
        'updated_at',
    ];

    /**
     * Get credentials for this integration
     */
    public function credentials()
    {
        return $this->hasMany(IntegrationCredential::class);
    }
}