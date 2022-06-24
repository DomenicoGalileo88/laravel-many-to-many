<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::orderByDesc('id')->get();
        return view('admin.tags.index', compact('tags'));
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
            'name' => 'required|unique:tags'
        ]);

        //generate slug
        $slug = Str::slug($request->name);
        $val_data['slug'] = $slug;

        //save
        Tag::create($val_data);

        //redirect
        return redirect()->back()->with('message', 'Tag successfully added!!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        //dd($request->all());

        //validation

        // aggiungere la classe Rule per far si che il titolo ignori lo unique
        $val_data = $request->validate([
            'name' => ['required', Rule::unique('tags',)->ignore('$tag')]
        ]);

        //generate slug
        $slug = Str::slug($request->name);
        $val_data['slug'] = $slug;

        //save
        $tag->update($val_data);

        //redirect
        return redirect()->back()->with('message', 'Tag $slug updated successfully!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->back()->with('message', 'Tag deleted!!');
    }
}
