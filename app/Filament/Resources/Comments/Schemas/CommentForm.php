<?php

namespace App\Filament\Resources\Comments\Schemas;

use App\Models\Post;
use App\Models\User;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CommentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('comment'),
                SpatieTagsInput::make('tags'),
                MorphToSelect::make('commentable')
                ->types([
                    MorphToSelect\Type::make(User::class)
                        ->titleAttribute('name'),
                    MorphToSelect\Type::make(Post::class)
                        ->titleAttribute('title'),
                ])
            ]);
    }
}
