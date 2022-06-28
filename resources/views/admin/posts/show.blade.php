@extends('layouts.admin')

@section('content')

<div class="container test-center my-5">
    <h1 class="my-4">Post {{$post->title}}</h1>


    <div class="content d-flex">
        <img class="shadow" width="300" src="{{asset('storage/' . $post->cover_image)}}" alt="{{$post->title}}">
        <div class="text ms-4">
            <div class="category">
                <strong>Category:</strong> {{$post->category ? $post->category->name : 'N/D'}}
            </div>

            <div class="tags">
                <strong>Tags:</strong>
                @if(count($post->tags) > 0)
                @foreach($post->tags as $tag)
                <small>#{{$tag->name}} </small>
                @endforeach
                @else
                <small>N/D</small>
                @endif
            </div>

            <p>{{$post->content}}</p>
        </div>
    </div>
</div>


@endsection