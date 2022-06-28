<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Mail\NewPosCreated;
use App\Mail\PostUpdate;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::where('user_id', Auth::id())->orderByDesc('id')->get();
        //dd($posts);
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {

        //ddd($request->all());

        // Validazione dati
        $val_data = $request->validated();

        // generare lo slug
        $slug = Post::slug($request->title); // lo generiamo attraverso una funzione definita nel metodo Post 
        $val_data['slug'] = $slug;

        // assign the post to the authenticated user
        $val_data['user_id'] = Auth::id();

        // verificare se la richiesta contiene un file
        if ($request->hasFile('cover_image')) {
            // validare il file
            $request->validate([
                'cover_image' => 'nullable|image|max:300'
            ]);
            // salvo il file nel filesystem
            // recupero il percorso
            //ddd($request->all());
            $path = Storage::put('post_images', $request->cover_image);
            // passo il percorso all'array di dati validati per salvare la risorsa
            $val_data['cover_image'] = $path;
        }

        // create the resource
        $new_post = Post::create($val_data);
        // attach all tags to the post
        $new_post->tags()->attach($request->tags);

        // invia una mail usando l'istanza dell'user nella request
        Mail::to($request->user())->send(new NewPosCreated($new_post));

        /* se la vuoi mandare inserendo un indirizzo mail
        Mail::to('example@example.com')->send(new NewPosCreated($new_post)); */

        // redirect
        return redirect()->route('admin.posts.index')->with('message', 'Post Created successfully!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::all();
        $tags = Tag::all();
        
        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, Post $post)
    {
        //dd($request->all());

        // Validazione dati
        $val_data = $request->validated();

        // generare lo slug
        $slug = Post::slug($request->title); // lo generiamo attraverso una funzione definita nel metodo Post
        $val_data['slug'] = $slug;

        // verificare se la richiesta contiene un file
        if ($request->hasFile('cover_image')) {
            // validare il file
            $request->validate([
                'cover_image' => 'nullable|image|max:300'
            ]);
            // elimino l'immagine vecchia
            Storage::delete($post->cover_image);
            // salvo il file nel filesystem
            // recupero il percorso
            //ddd($request->all());
            $path = Storage::put('post_images', $request->cover_image);
            // passo il percorso all'array di dati validati per salvare la risorsa
            $val_data['cover_image'] = $path;
        }

        // update the resource
        $post->update($val_data);

        //sync tags
        $post->tags()->sync($request->tags);

        //invia la mail
        Mail::to('admin@boolpress.com')->send(new PostUpdate($post));


        // redirect
        return redirect()->route('admin.posts.index')->with('message', 'Post modificato con successo');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        // elimino l'immagine vecchia
        Storage::delete($post->cover_image);
        //elimino la risorsa
        $post->delete();
        return redirect()->route('admin.posts.index')->with('message', 'Post eliminato con successo');
    }
}
