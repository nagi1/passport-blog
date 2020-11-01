<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use App\Http\Resources\PostResource;
use App\Http\Resources\CommentResource;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::with('user')->paginate(request()->get('limit', 10));
        return CommentResource::collection($comments);
    }

    public function show(Comment $comment)
    {
        $comment = $comment->load(['user']);
        return new CommentResource($comment);
    }
}
