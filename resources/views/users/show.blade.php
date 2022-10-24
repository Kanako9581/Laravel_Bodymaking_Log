@extends('layouts.logged_in')
@section('title')
@section('content')
    <h1>{{ $title }}</h1>
    <div class="container_profile">
        <div>
            @if($user->image !== '')
                <img src="{{ \Storage::url($user->image) }}" class="profile_image">
            @else
                <img src="{{ asset('images/no_image.png') }}">
            @endif
        </div>
        <div>
            <h2>{{ $user->name }}さん</h2>
                @if($user->isFollowing($user))
                    <form method="post" action="{{ route('follows.destroy', $user) }}" class="follow">
                        @csrf
                        @method('delete')
                        <input type="submit" value="フォロー解除" class="follow_button">
                    </form>
                @else
                    <form method="post" action="{{ route('follows.store', $user) }}" class="follow">
                        @csrf
                        <input type="hidden" name="follow_id" value="{{ $user->id }}">
                        <input type="submit" value="フォロー" class="follow_button">
                    </form>
                @endif
            <p>
                <a href="{{ route('follows.index') }}" class="profile_follows">フォロー数:{{ $user->follow_users()->get()->count() }}</a> 
                <a href="{{ route('follows.follower', $user) }}" class="profile_follows">フォロワー数:{{ $user->followers()->get()->count() }}</a>
            </p>
            <p>投稿数: {{ $user->posts()->get()->count() }}</p>
            @if($user->profile !== '')
                <p>{{ $user->profile }}</p>
            @else
                <p>プロフィールが設定されていません</p>
            @endif
        </div>
    </div>
    <h2 class="title_h2">{{ $user->name }}さんの最新の投稿</h2>
    @forelse($user_posts as $post)
        <li class="post">
            <div class="post_content_heading">
                <h3><a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a></h3>
                ({{ $post->created_at }} 最終更新日:{{ $post->updated_at}})
                <p>カテゴリー: {{ $post->category->name }}</p>
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
                <div class="tags">
                    タグ：
                    @forelse($post->tags as $tag)
                        <a href="{{ route('posts.tag', $tag) }}">{{ $tag->tag }}</a>
                    @empty
                        <span> </span>
                    @endforelse
                </div>
            </div>
        </li>
    @empty
        <li>投稿はありません</li>
    @endforelse
    {{ $user_posts->links() }}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $('like_button').each(function(){
            $(this).on('click', function(){
                $(this).next().submit();
            });
        });
    </script>
@endsection