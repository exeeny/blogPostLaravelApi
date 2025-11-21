<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\IdentifiesModel;
use App\Models\Comment;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CommentController extends Controller implements HasMiddleware
{
    use IdentifiesModel;

    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];
    }

    public function index(string $type, int $id)
    {
        $model = $this->identifyModel($type, $id);
        return CommentResource::collection($model->comments()->get());
    }
    public function store(StoreCommentRequest $request, string $type, int $id)
    {
        $model = $this->identifyModel($type, $id);
        $comment = $model->comments()->create($request->validated());
        if($request->tags){
            $model->attachTags($request->tags);
        }
        
        return new CommentResource($comment);
    }

    public function show(Comment $comment)
    {
        $comment->load('tags');
        return new CommentResource($comment);
    }

    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $comment->update($request->validated());
        if ($request->tags)
        {
            $comment->syncTags($request->tags);
        }
        
        return new CommentResource($comment);
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->noContent();
    }
}
