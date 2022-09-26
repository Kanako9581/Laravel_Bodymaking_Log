<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>@yield('title')</title>
        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c&display=swap" rel="stylesheet">
        <script src="https://kit.fontawesome.com/02dcd5cd6f.js" crossorigin="anonymous"></script>
    </head>
    <body>
    
        {{-- エラーメッセージを出力 --}}
        @foreach($errors->all() as $error)
          <p class="error">{{ $error }}</p>
        @endforeach
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