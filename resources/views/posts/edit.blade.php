@extends('layouts.logged_in')
@section('title')
@section('content')
    <h1>{{ $title }}</h1>
    <form method="post" action="{{ route('posts.update', $post) }}" enctype="multipart/form-data" class="form">
        @csrf
        @method('patch')
        <div>
            <label>
                <input type="text" name="title" placeholder="タイトルを入力" value="{{ $post->title }}" class="form_title">
            </label>
        </div>
        <div>
            <label>
                <textarea name="article" rows="50" cols="50">{{ $post->article }}</textarea>
            </label>
        </div>
        <div>
            <label>
                <input type="text" name="post_tags_string" placeholder="タグを入力" class="form_tag" value="{{ $post->post_tags_string }}">
            </label>
        </div>
        <div>
            <label>
                カテゴリー:
                <select name="category_id" class="form_category">
                    @foreach($category_ids as $category_id)
                        <option value="{{ $category_id->id }}" {{ $category_id->id === $post->category_id ? 'selected' : ''}}>{{ $category_id->name }}</option>
                    @endforeach
                </select>
            </label>
        </div>
        <div>
            <label>
                <input type="file" name="image[]" multiple>
            </label>
        </div>
        <input type="submit" value="投稿" class="form_submit">
    </form>
@endsection