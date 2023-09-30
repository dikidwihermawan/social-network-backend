<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Status extends Model
{
    use HasFactory;

    protected $fillable = [
        'identifier',
        'body'
    ];

    public function getRouteKeyName()
    {
        return 'identifier';
    }

    protected $with = ['user', 'hasLike'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function hasLike()
    {
        return $this->hasOne(Like::class)->where('likes.user_id', Auth::user()->id);
    }
    public function likesCount()
    {
        return $this->hasMany(Like::class)->where('type', 1);
    }
    public function dislikesCount()
    {
        return $this->hasMany(Like::class)->where('type', 0);
    }
    public function hasComment($id)
    {
        return $this->hasMany(Comment::class)->where('comment.comment_id', $id);
    }
}
