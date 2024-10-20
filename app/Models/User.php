<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'address',
        'number',
        'usertype',
        'email',
        'password',
    ];

    public function scopeSearch($query, $val){
        return $query->where(function($q) use ($val) {
            $q->where('id', 'like', '%'.$val.'%')
              ->orWhere('name', 'like', '%'.$val.'%')
              ->orWhere('address', 'like', '%'.$val.'%')
              ->orWhere('usertype', 'like', '%'.$val.'%')
              ->orWhere('email', 'like', '%'.$val.'%');
        });
    }

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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
