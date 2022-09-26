@extends('layouts.logged_in')
@section('title')
@section('content')
    <h1>{{ $title }}</h1>
    <form action="{{ route('users.update', $user) }}" method="post" class="form">
        @csrf
        @method('patch')
        <div>
            <label>
                名前: <input name="name" type="text" value="{{ $user->name }}">
            </label>
        </div>
        <div>
            <label>
                プロフィール: <textarea name="profile" rows="30" cols="30">{{ $user->profile }}</textarea>
            </label>
        </div>
        <input type="submit" value="更新" class="form_submit">
    </form>
@endsection