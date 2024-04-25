<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChannelPermission extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'channel_id', 'can_join', 'can_post', 'can_view_messages'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    
}
