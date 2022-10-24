@extends('layouts.logged_in')
@section('title')
@section('content')
    <h1>{{ $title }}</h1>
    <ul class="follow_users">
        @forelse($follow_users as $follow_user)
            <li class="follow_list">
                <div>
                    @if($follow_user->image !== '')
                        <img src="{{ \Storage::url($follow_user->image) }}" class="profile_image_icon">
                    @else
                        <img src="{{ asset('images/no_image.png') }}" class="profile_image_icon">
                    @endif
                </div>
                <div>
                    <a href="{{ route('users.show', $follow_user) }}">{{ $follow_user->name }}</a>
                </div>
                <div>
                    <form method="post" action="{{ route('follows.destroy', $follow_user) }}" class="follow">
                        @csrf
                        @method('delete')
                        <input type="submit" value="フォロー解除" class="follow_button">
                    </form>
                </div>
            </li>
        @empty
            <li>フォローしているユーザーはいません</li>
        @endforelse
    </ul>
@endsection