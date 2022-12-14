@extends('layouts.logged_in')
@section('title')
@section('content')
    <h1>{{ $title }}</h1>
    <div class="container">
        <a href="{{ route('posts.create') }}" class="post_create"><i class="fa-regular fa-pen-to-square"></i> 新規投稿</a>
        <div>
            <form action="{{ route('top') }}" method="get" class="form_search">
                <input type="text" value="{{ $keyword }}" name="keyword" class="form_search_text">
                <input type="submit" value="検索" class="form_search_submit">
            </form>
        </div>
        <h2>おすすめユーザー</h2>
        <ul class="recommended_user">
            @forelse($recommended_users as $recommended_user)
                <li>
                    <div>
                        <a href="{{ route('users.show', $recommended_user) }}">
                        @if($recommended_user->image !== '')
                            <img src="{{ \Storage::url($recommended_user->image) }}" class="profile_image_icon">
                        @else
                            <img src="{{ asset('images/no_image.png') }}">
                        @endif
                        {{ $recommended_user->name }}
                    </a>
                    </div>
                    <div>
                        @if(Auth::user()->isFollowing($recommended_users))
                            <form method="post" action="{{ route('follow.destroy', $recommended_user) }}" class="follow">
                                @csrf
                                @method('delete')
                                <input type="submit" value="フォロー解除" class="follow_button">
                            </form>
                        @else
                            <form method="post" action="{{ route('follows.store') }}" class="follow">
                                @csrf
                                <input type="hidden" name="follow_id" value="{{ $recommended_user->id }}">
                                <input type="submit" value="フォロー" class="follow_button">
                            </form>
                        @endif
                    </div>
                </li>
            @empty
                <li>おすすめユーザーはいません</li>
            @endforelse
        </ul>
    </div>
    <ul>
        @forelse($posts as $post)
            <li class="post">
                <div class="post_content">
                    <div class="post_content_heading">
                        <h2>{{ $post->title }}</h2>
                        <a href="{{ route('users.show', $post->user) }}">
                        @if($post->user->image !== '')
                            <img src="{{ \Storage::url($post->user->image) }}" class="profile_image_icon">
                        @else
                            <img src="{{ asset('images/no_image.png') }}">
                        @endif
                            {{ $post->user->name }}
                        </a> ({{ $post->created_at }} 最終更新日:{{ $post->updated_at }})
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
                    <div class="post_content_footer">
                        <div>
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
                        @if($post->user_id === $user->id)
                        <div>
                            [<a href="{{ route('posts.edit', $post)}}">編集</a>]
                        </div>
                        <div>
                            <form method="post" class="delete" action="{{ route('posts.destroy', $post) }}">
                                @csrf
                                @method('delete')
                                <input type="submit" value="削除">
                            </form>
                        </div>
                        @endif
                    </div>
                    <div class="post_comments">
                        <span class="post_comments_header">コメント</span>
                        <ul class="post_comments_body">
                            @forelse($post->comments as $comment)
                                <li>{{ $comment->user->name }}: {{ $comment->body }}</li>
                            @empty
                                <li>コメントはありません</li>
                            @endforelse
                        </ul>
                        <form method="post" action="{{ route('comments.store') }}" class="form_comment">
                            @csrf
                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                            <!--<label>-->
                                <!--<input type="text" name="body" placeholder="コメントを書く" class="form_comment_text">-->
                            <!--</label>-->
                            <div class="flex_comment_form">
                                <div class="flex_comment_form_dummy" aria-hidden="true"></div>
                                <textarea class="flex_comment_form_textarea"></textarea>
                            </div>
                            <input type="submit" value="送信" class="form_send">
                        </form>
                    </div>
                </div>
            </li>
        @empty
            <li>投稿はありません</li>
        @endforelse
    </ul>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $('.like_button').each(function(){
            $(this).on('click', function(){
                $(this).next().submit();
            });
        });
        function flex_comment_form(el){
            const dummy = el.querySelector('.flex_comment_form_dummy');
            el.querySelector('.flex_comment_form_textarea').addEventListener('input', e => {
                 dummy.textContent = e.target.value + '\u200b';
            });
         }
         document.querySelectorAll('.flex_comment_form').forEach(flex_comment_form);
         //let selectors = Array.from(document.querySelectorAll('.flex_comment_form'));//.foreach(flex_comment_form);
         //for(let selector of selectors){
         //   flex_comment_form(selector);
         //}
    </script>
@endsection