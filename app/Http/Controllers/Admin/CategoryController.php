<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::withCount('posts')->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.categories.create');
    }


    /**
     * Show the category
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Category $category)
    {
        //check if it's an API request
        if ($request->wantsJson()) {
            return response()->json([
                'data' => [
                    'category' => new CategoryResource($category)
                ],
            ]);
        }
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

        $category = Category::create(['name' => $request->name]);

        //check if it's an API request
        if ($request->wantsJson()) {
            return response()->json([
                'data' => [
                    'message' => 'Category created successfully',
                    'category' => new CategoryResource($category)
                ],
            ]);
        }

        flash()->overlay('Category created successfully');

        return redirect('/admin/categories');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $this->validate($request, ['name' => 'required']);

        $category->update($request->all());

        //check if it's an API request
        if ($request->wantsJson()) {
            return response()->json([
                'data' => [
                    'message' => 'Category updated successfully',
                    'category' => new CategoryResource($category)
                ],
            ]);
        }

        flash()->overlay('Category updated successfully');

        return redirect('/admin/categories');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();

        //check if it's an API request
        if (request()->wantsJson()) {
            return response()->json([
                'data' => [
                    'message' => 'Category deleted successfully',
                    'category' => new CategoryResource($category)
                ],
            ]);
        }

        flash()->overlay('Category deleted successfully');

        return redirect('/admin/categories');
    }
}
