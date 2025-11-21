<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Tags\HasTags;

// [ScopedBy([ExcludeBannedScope::class])]
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'is_banned',
        'is_active',
        'password',
    ];

    protected function name(): Attribute
    {
    return Attribute::make(
        get: fn($value)=>ucfirst($value),
        set: fn($value)=> strtolower($value)
    );
    }
    protected function email(): Attribute
    {
        return Attribute::make(
            set: fn($value)=> strtolower($value)
        );
    }

    public function posts(){
        return $this->hasMany(Post::class);
    }

    public function latestPost()
    {
        return $this->hasOne(Post::class)->latestOfMany();

    }

    public function comments(){
        return $this->morphMany(Comment::class, 'commentable');
    }

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', 1);
    }

    #[Scope]
    protected function registeredThisMonth(Builder $query): void
    {
        $query->whereMonth(
            'created_at',now()->month,
        );
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            'is_active' => 'boolean',
            'is_banned' => 'boolean',
        ];
    }
}
