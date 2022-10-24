@extends('layouts.logged_in')
@section('title')
@section('content')
    <h1>{{ $title }}</h1>
    <ul class="follower">
        @forelse($followers as $follower)
            <li class="follower follow_list">
                <div>
                    @if($follower->image !== '')
                        <img src="{{ \Storage::url($follower->image) }}" class="profile_image_icon">
                    @else
                        <img src="{{ asset('images/no_image.png') }}" class="profile_image_icon">
                    @endif
                </div>
                <div>
                    <a href="{{ route('users.show', $follower) }}">{{ $follower->name }}</a>
                </div>
                <div>
                    @if(Auth::user()->isFollowing($follower))
                        <form method="post" action="{{ route('follows.destroy', $follower) }}" class="follow">
                            @csrf
                            @method('delete')
                            <input type="submit" value="フォロー解除" class="follow_button">
                        </form>
                    @else
                        <form method="post" action="{{ route('follows.store') }}" class="follow">
                            @csrf
                            <input type="hidden" name="follow_id" value="{{ $follower->id }}">
                            <input type="submit" value="フォロー" class="follow_button">
                        </form>
                    @endif
                </div>
            </li>
        @empty
            <li>フォロワーはいません</li>
        @endforelse
    </ul>
@endsection