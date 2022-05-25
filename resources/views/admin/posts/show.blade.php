@extends('layouts.admin')

@section('pageTitle', $post->title)

@section('pageContent')
{{-- {{dd($post)}} --}}
    <div class="container">
        <div class="row">
            <div class="col">
                <h1>{{ $post->title }}</h1>
                <p>{{ $post->content }}</p>
                {{-- {{dd($post->tags)}} --}}
                <p>Post Tags:
                    @foreach ($post->tags as $tag)
                         {{$tag->name}} |
                    @endforeach
                </p>
                <p>Post by: {{$post->user->name}}</p>
                <p>Contact: {{$post->user->userInfo->phone}}</p>
            </div>
        </div>
        <a href="{{ url()->route('admin.posts.index') }}" class="btn btn-primary">Back</a>
    </div>
@endsection
