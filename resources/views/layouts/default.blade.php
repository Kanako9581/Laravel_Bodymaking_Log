<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>@yield('title')</title>
        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    </head>
    <body>
        @yield('header')
        @foreach($errors as $error)
            <p class="error">{{ $error }}</p>
        @endforeach
        @if(session()->has('success'))
            <div class="success">
                {{ session()->get('success') }}
            </div>
        @endif
        @yield('content')
    </body>
</html>