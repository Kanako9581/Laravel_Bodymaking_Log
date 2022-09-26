@extends('layouts.logged_in')
@section('title')
@section('content')
    <h1>{{ $title }}</h1>
    <div class="container_profile">
        <div>
            @if($user->image !== '')
                <img src="{{ \Storage::url($user->image) }}" class="profile_image"><a href="{{ route('users.edit_image', $user) }}">画像を変更</a>
            @else
                <a href="{{ route('users.edit_image', $user) }}"><img src="{{ asset('storage/images/no_image.png') }}">画像を登録</a>
            @endif
        </div>
        <div>
            <h2>{{ $user->name }}さん</h2>
            <p>
                <a class="profile_follows" href="{{ route('follows.index') }}">フォロー数:{{ $user->follow_users()->get()->count() }}</a> 
                <a class="profile_follows" href="{{ route('follows.follower', $user) }}">フォロワー数:{{ $user->followers()->get()->count() }}</a>
            </p>
            <p>投稿数: {{ $user_posts_count }}</p>
            @if($user->profile !== '')
                <p>{{ $user->profile }} <a href="{{ route('users.edit', $user) }}">プロフィール編集</a></p>
            @else
                <p>プロフィールが設定されていません <a href="{{ route('users.edit', $user) }}">プロフィール設定</a></p>
            @endif
        </div>
    </div>
    <h2 class="title_h2">投稿一覧</h2>
    @forelse($user_posts as $post)
        <li class="post">
            <div class="post_content_heading">
                <h3><a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a></h3>
                ({{ $post->created_at }} 最終更新日:{{ $post->updated_at}})
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
        </li>
    @empty
        <li>投稿はありません</li>
    @endforelse
    {{ $user_posts->links() }}
@endsection