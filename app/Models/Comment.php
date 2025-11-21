<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;

class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory, HasTags;
     protected $fillable = ['comment', 'commentable_type', 'commentable_id'];
    
    public function commentable(){
        return $this->morphTo();
    }

}
