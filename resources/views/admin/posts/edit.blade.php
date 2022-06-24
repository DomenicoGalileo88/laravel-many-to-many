@extends('layouts.admin')

@section('content')

<h2>Edit {{$post->title}}</h2>

@include('partials.errors')

<form action="{{route('admin.posts.update', $post->slug)}}" method="post">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" name="title" id="title" class="form-control" placeholder="Learn php article" aria-describedby="titleHelper" value="{{old('title', $post->title)}}">
        <small id="titleHelper" class="text-muted">Type the post title, max: 150 carachters</small>
    </div>
    <!-- TODO: Change to input type file -->
    <div class="form-group">
        <label for="cover_image">cover_image</label>
        <input type="text" name="cover_image" id="cover_image" class="form-control" placeholder="Learn php article" aria-describedby="cover_imageHelper" value="{{old('cover_image', $post->cover_image)}}">
        <small id="cover_imageHelper" class="text-muted">Type the post cover_image</small>
    </div>

    <div class="mb-3">
        <label for="category_id" class="form-label">Categories</label>
        <select class="form-control @error('category_id') is-invalid @enderror" name="category_id" id="category_id">
            <option value="">Select category</option>
            @foreach($categories as $category)
            <option value="{{$category->id}}" {{old('category_id', $post->category_id) == $category->id ? 'selected' : ''}}>{{$category->name}}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="tags" class="form-label">Tags</label>
        <select multiple class="form-select" name="tags[]" id="tags" aria-label="Tags">
            <option value="" disabled>Select tags</option>
            @forelse($tags as $tag)
            @if($errors->any())
            <option value="{{$tag->id}}" {{ in_array($tag->id, old('tags', [])) ? 'selected' : ''}}>{{$tag->name}}</option>
            @else
            <option value="{{$tag->id}}" {{ $post->tags->contains($tag->id) ? 'selected' : ''}}>{{$tag->name}}</option>
            @endif
            @empty
            <option>No tag! Add firs tags!!</option>
            @endforelse
        </select>
    </div>

    <div class="mb-3">
        <label for="content">Content</label>
        <textarea class="form-control" name="content" id="content" rows="4">{{old('content', $post->content)}}</textarea>
    </div>

    <button type="submit" class="btn btn-primary text-white mt-4">Edit Post</button>

</form>

@endsection