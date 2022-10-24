@extends('layouts.default')
@section('header')
    <header>
        <ul class="header_nav">
            <li>
                <a href="{{ route('posts.index') }}" class="top_logo">みんなのボディメイク日記</a>
            </li>
            <li>
                <a href="{{ route('users.index') }}">ユーザープロフィール</a>
            </li>
            <li>
                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <input type="submit" value="ログアウト" class="logged_out">
                </form>
            </li>
        </ul>
        <p>{{ Auth::user()->name }}さん、こんにちは！</p>
    </header>
@endsection
@section('content')
    <footer>
        <p><small>&copy;みんなのボディメイク日記 All Rights Reserved.</small></p>
    </footer>
@endsection