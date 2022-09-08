@extends('layouts.logged_in')
@section('title')
@section('content')
    <h1>{{ $title }}</h1>
    <a href="{{ route('posts.create') }}">新規投稿</a>
    <h2>おすすめユーザー</h2>
    <ul class="recommended_user">
        @forelse($recommended_users as $recommended_user)
            <li>
                <a href="{{ route('users.show', $recommended_user) }}">{{ $recommended_user->name }}</a>
                @if(Auth::user()->isFollowing($recommended_users))
                    <form method="post" action="{{ route('follow.destroy', $recommended_user) }}" class="follow">
                        @csrf
                        @method('delete')
                        <input type="submit" value="フォロー解除">
                    </form>
                @else
                    <form method="post" action="{{ route('follows.store') }}" class="follow">
                        @csrf
                        <input type="hidden" name="follow_id" value="{{ $recommended_user->id }}">
                        <input type="submit" value="フォロー">
                    </form>
                @endif
            </li>
        @empty
            <li>おすすめユーザーはいません</li>
        @endforelse
    </ul>
    <ul>
        @forelse($posts as $post)
            <li class="post">
                
            </li>
        @empty
            <li>投稿はありません</li>
        @endforelse
    </ul>
    <script>
        $('like_button').each(function(){
            $(this).on('click', function(){
                $(this).next().submit();
            });
        });
    </script>
@endsection