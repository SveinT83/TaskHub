<?php

namespace Modules\FacebookPostingModule\Models;

use Illuminate\Database\Eloquent\Model;

class FacebookPost extends Model
{
    protected $fillable = ['user_id', 'group_id', 'message', 'response'];
}
