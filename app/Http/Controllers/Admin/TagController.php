<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Tag;
use App\Http\Resources\TagResource;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::paginate(10);

        return view('admin.tags.index', compact('tags'));
    }


    /**
     * Show the tag
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Tag $tag)
    {
        //check if it's an API request
        if ($request->wantsJson()) {
            return response()->json([
                'data' => [
                    'tag' => new TagResource($tag)
                ],
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tags.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, ['name' => 'required']);

        $tag = Tag::create(['name' => $request->name]);

        //check if it's an API request
        if ($request->wantsJson()) {
            return response()->json([
                'data' => [
                    'message' => 'Tag created successfully',
                    'tag' => new TagResource($tag)
                ],
            ]);
        }
        flash()->overlay('Tag created successfully.');

        return redirect('/admin/tags');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        $this->validate($request, ['name' => 'required']);

        $tag->update($request->all());

        //check if it's an API request
        if ($request->wantsJson()) {
            return response()->json([
                'data' => [
                    'message' => 'Tag updated successfully',
                    'tag' => new TagResource($tag)
                ],
            ]);
        }

        flash()->overlay('Tag updated successfully.');

        return redirect('/admin/tags');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();
        //check if it's an API request
        if (request()->wantsJson()) {
            return response()->json([
                'data' => [
                    'message' => 'Tag deleted successfully',
                ],
            ]);
        }

        flash()->overlay('Tag deleted successfully.');

        return redirect('/admin/tags');
    }
}
