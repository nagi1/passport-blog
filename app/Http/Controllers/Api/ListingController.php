<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\User;
use App\Models\Tag;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Category;
use App\Http\Resources\UserResource;
use App\Http\Resources\TagResource;
use App\Http\Resources\CategoryResource;
use App\Http\Controllers\Controller;

class ListingController extends Controller
{

    public function stats()
    {

        $posts = Post::count();
        $comments = Comment::count();
        $tags = Tag::count();
        $categories = Category::count();

        return response()->json([
            'data' => [
                'stats' => get_defined_vars(),
            ],
        ]);
    }


    public function tags()
    {
        $tags = Tag::paginate(10);

        return TagResource::collection($tags);
    }

    public function categories()
    {
        $categories = Category::paginate(10);

        return CategoryResource::collection($categories);
    }
}
