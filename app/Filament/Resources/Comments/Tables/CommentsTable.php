<?php

namespace App\Filament\Resources\Comments\Tables;

use App\Models\Post;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CommentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('comment'),
                TextColumn::make('commentable')->state(function ( $record) {
                    $model = $record->commentable; // the actual Post or User model

        if ($model instanceof Post) {
            return 'Post: ' . $model->title;
        }

        if ($model instanceof User) {
            return 'User: ' . $model->name;
        }}),
                SpatieTagsColumn::make('tags')
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
