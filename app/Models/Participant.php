<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'profile',
        'timeline',
        'status',
    ];


//    protected $casts = [
//        'created_at' => 'datetime:jS F Y, h:i:s A',
//    ];

    public function scopeActive($query): void
    {
        $query->whereStatus(1);
    }

    public function tweets(): HasMany
    {
        return $this->hasMany(Tweet::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(TweetLike::class, 'participant_id');
    }

    public function followers()
    {
        return $this->hasMany(Follower::class, 'participant_id');
    }

    public function following()
    {
        return $this->hasMany(Follower::class, 'follower_id');
    }
}
