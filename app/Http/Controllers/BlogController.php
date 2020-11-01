<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::when($request->search, function ($query) use ($request) {
            $search = $request->search;

            return $query->where('title', 'like', "%$search%")
                ->orWhere('body', 'like', "%$search%");
        })->with('tags', 'category', 'user')
            ->withCount('comments')
            ->published()
            ->simplePaginate(5);

        return view('frontend.index', compact('posts'));
    }

    public function post(Post $post)
    {
        $post = $post->load(['comments.user', 'tags', 'user', 'category']);

        return view('frontend.post', compact('post'));
    }

    public function comment(Request $request, Post $post)
    {
        $this->validate($request, ['body' => 'required']);

        $post->comments()->create([
            'body' => $request->body,
            'user_id' => $request->user_id ? $request->user_id : auth()->guard('api')->user()->id
        ]);

        //check if it's an API request
        if (request()->wantsJson()) {
            return response()->json([
                'data' => [
                    'message' => 'Comment successfully created',
                ],
            ]);
        }

        flash()->overlay('Comment successfully created');

        return redirect("/posts/{$post->id}");
    }
}
