<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserImageRequest;
use App\Post;
use App\User;
use App\Follow;
use App\Http\Service\FileUploadService;

class UserController extends Controller
{
    public function index(){
        $user = \Auth::user();
        $user_posts = $user->posts()->orderBy('created_at', 'desc')->paginate(5);
        $user_posts_count = $user->posts()->get()->count();
        return view('users.index', [
            'title' => 'マイアカウントページ',
            'user' => $user,
            'user_posts' => $user_posts,
            'user_posts_count' => $user_posts_count,
        ]);
    }
    public function show($id){
        $user = User::find($id);
        $user_posts = $user->posts()->orderBy('created_at', 'desc')->paginate(5);
        return view('users.show', [
            'title' => 'ユーザー詳細',
            'user' => $user,
            'user_posts' => $user_posts,
        ]);
    }
    public function edit(){
        $user = \Auth::user();
        return view('users.edit', [
            'title' => 'プロフィール編集',
            'user' => $user,
        ]);
    }
    public function update(UserRequest $request){
        $user = \Auth::user();
        $user->update($request->only(['name', 'profile']));
        session()->flash('success', 'プロフィールを編集しました');
        return redirect()->route('users.index', $user);
    }
    public function editImage(){
        $user = \Auth::user();
        return view('users.edit_image', [
            'title' => 'プロフィール画像変更画面',
            'user' => $user,
        ]);
    }
    public function updateImage(UserImageRequest $request, FileUploadService $service){
        $user = \Auth::user();
        $path = $service->saveImage($request->file('image'));
        if($user->image !== ''){
            \Storage::disk('public')->delete(\Storage::url($user->image));
        }
        $user->update([ 'image' => $path, ]);
        session()->flash('success', 'プロフィール画像を変更しました');
        return redirect()->route('users.index', $user);
    }
}
