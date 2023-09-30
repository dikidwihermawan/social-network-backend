<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getRouteKeyName()
    {
        return 'username';
    }

    public function follows()
    {
        return $this->belongsToMany(User::class, 'follows', 'user_id', 'following_user_id')->withTimestamps();
    }

    public function follow($user)
    {
        return $this->follows()->save($user);
    }
    public function unfollow($user)
    {
        return $this->follows()->detach($user);
    }

    public function hasFollow($user)
    {
        return $this->follows()->where('following_user_id', $user->id)->exists();
    }

    public function hasFollows($users)
    {
        foreach ($users as $u => $index) {
            if (!$this->follows()->where('following_user_id', $users[$u]->id)->exists()) {
                $users[$u]['follow'] = false;
            } else {
                $users[$u]['follow'] = true;
            }
        }
        return $users;
    }
}
