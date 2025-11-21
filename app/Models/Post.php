<?php

namespace App\Models;

use App\Models\Scopes\PublishedScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

// #[ScopedBy([PublishedScope::class])]
class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory, HasTags;

    protected $fillable = [
        'title','content','user_id', 'is_published'
    ];

    protected function title(): Attribute
    {
    return Attribute::make(
        get: fn($value)=>ucfirst($value),
        set: fn($value)=> strtolower($value)
    );
    }
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function comments(){
        return $this->morphMany(Comment::class, 'commentable');
    }

    #[Scope]
    protected function trending(Builder $query): void
    {
        $query->withCount('comments')->where('comments_count', '>', 10);
    }

    #[Scope]
    protected function draft(Builder $query): void
    {
        $query->withAttributes([
            'is_published' => false,
        ]);
    }

     protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

}
