<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChannelKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'channel_name',
        'api_key',
        'api_secret',
    ];
}
