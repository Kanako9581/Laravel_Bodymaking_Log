@extends('layouts.logged_in')
@section('title')
@section('content')
    <h1>{{ $title }}</h1>
    <div>
        <form action="{{ route('posts.search') }}" method="get">
            <input type="text" value="{{ $keyword }}" name="keyword">
            <input type="submit" value="検索">
        </form>
    </div>
    @foreach($posts as $post)
        <li class="post">
            <div class="post_content">
                <div class="post_content_heading">
                    <h2>{{ $post->title }}</h2>
                    <a href="{{ route('users.show', $post->user) }}">{{ $post->user->name }}</a> ({{ $post->created_at }} 最終更新日:{{ $post->updated_at }})
                </div>
                <div class="post_content_main">
                    <div class="post_content_main_img">
                        @foreach($post->postImages as $image)
                            <img src="{{ \Storage::url($image) }}">
                        @endforeach
                    </div>
                    <article class="post_content_main_article">
                        {{ $post->article }}
                    </article>
                </div>
                <div class="post_content_footer">
                    <a class="like_button">
                        @if($post->isLikedBy(Auth::user()))
                            <i class="fa-solid fa-heart"></i>
                        @else
                            <i class="fa-regular fa-heart"></i>
                        @endif
                    </a>
                    <form class="like" method="post" action="{{ route('posts.toggle_like', $post) }}">
                        @csrf
                        @method('patch')
                    </form>
                </div>
            </div>
        </li>
    @endforeach
@endsection
        