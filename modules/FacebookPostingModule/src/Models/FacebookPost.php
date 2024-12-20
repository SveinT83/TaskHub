<?php

namespace Modules\FacebookPostingModule\src\Models;

use Illuminate\Database\Eloquent\Model;

class FacebookPost extends Model
{
    protected $fillable = ['user_id', 'group_id', 'message', 'response'];
}
