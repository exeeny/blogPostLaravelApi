<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];
    }
    public function index(Request $request)
    {
        return PostResource::collection(Post::with(['user', 'tags'])->withCount('comments')->when($request->tags, function ($query) use ($request) {
            $tags = explode(',', $request->tags);
            $query->withAnyTags($tags);
        })
            ->when(
                $request->author,
                function ($query) use ($request) {
                    $query->where('user_id', $request->author);
                }
            )
            ->when(
                $request->search,
                function ($query) use ($request) {
                    $query->where('title', 'like', '%' . $request->search . '%');
                }
            )
            ->when(
                $request->draft,
                function ($query) use ($request) {
                    $query->draft();
                }
            )
            ->when($request->sort, function ($query) use ($request) {
                switch ($request->sort) {
                    case 'comments':
                        $query->orderBy('comments_count', 'desc');
                        break;
                    case 'oldest':
                        $query->orderBy('created_at', 'asc');
                        break;
                }
            }, function ($query) {
                $query->latest();
            })->get());
    }

    public function show(Post $post)
    {
        $post->load(['comments', 'tags', 'user']);
        return new PostResource($post);
    }

    public function store(Request $request, StorePostRequest $validationrequest)
    {
        
        $post = $request->user()->posts()->draft()->create($validationrequest->validated());
        if ($request->tags) {
            $post->attachTags($request->tags);
        }

        return new PostResource($post);
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        Gate::authorize('modify', $post);
        $post->update($request->validated());
        if ($request->tags) {
            $post->syncTags($request->tags);
        }
        return new PostResource($post);
    }

    public function destroy(Post $post)
    {
        Gate::authorize('modify', $post);
        $post->delete();
        return response()->noContent();
    }
}
