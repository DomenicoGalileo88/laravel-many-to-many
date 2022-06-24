<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::orderByDesc('id')->get();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());

        //validation
        $val_data = $request->validate([
            'name' => 'required|unique:categories'
        ]);

        //generate slug
        $slug = Str::slug($request->name);
        $val_data['slug'] = $slug;

        //save
        Category::create($val_data);

        //redirect
        return redirect()->back()->with('message', 'Category successfully added!!');
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //dd($request->all());

        //validation

        // aggiungere la classe Rule per far si che il titolo ignori lo unique
        $val_data = $request->validate([
            'name' => ['required', Rule::unique('categories',)->ignore('$category')]
        ]);

        //generate slug
        $slug = Str::slug($request->name);
        $val_data['slug'] = $slug;

        //save
        $category->update($val_data);

        //redirect
        return redirect()->back()->with('message', 'Category $slug updated successfully!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->back()->with('message', 'Category deleted!!');
    }
}
