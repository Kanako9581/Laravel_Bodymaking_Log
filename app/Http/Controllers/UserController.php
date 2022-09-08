<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Post;
use App\User;
use App\Follow;
use App\Service\FileUploadService;

class UserController extends Controller
{
    public function index(){
        $user = \Auth::user();
        $user_posts = $user->posts()->latest()->get();
        $following_users_count = $user->follow_users()->count();
        $followers_count = $user->followers()->count();
        return view('users.index', [
            'title' => 'マイアカウントページ',
            'user' => $user,
            'user_posts' => $user_posts,
            'following_users_count' => $following_users_count,
            'followers_count' => $followers_count,
        ]);
    }
    public function edit(){
        $user = \Auth::user();
        return view('users.edit', [
            'title' => 'プロフィール編集',
            'user' => $user,
        ]);
    }
    public function update(UserRequest $request, FileUploadService $service){
        $user = \Auth::user();
        $user->update($request->only(['name', 'profile']));
        session()->flash('success', 'プロフィールを編集しました');
        return redirect()->route('users.index', $user);
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
