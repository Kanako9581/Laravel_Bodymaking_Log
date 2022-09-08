<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use App\User;
use App\Post;
use App\Like;
use App\Follow;
use App\Service\FileUploadService;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){
        $this->middleware('auth');
    }
    public function index()
    {
        $user = \Auth::user();
        $user_id = \Auth::id();
        $posts = Post::orderBy('created_at', 'desc')->where('user_id', '!=', $user_id)->get();
        return view('top', [
            'title' => 'みんなの投稿',
            'user' => $user,
            'posts' => $posts,
            'recommended_user' => User::recommend($user->id)->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create', [
            'title' => '新規投稿',
            'category_ids' => Category::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request, FileUploadService $service)
    {
        $path = $service->saveImage($request->file('image'));
        Post::create([
            'user_id' => \Auth::user()->id,
            'name' => \Auth::user()->name,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'article' => $request->article,
            'image' => $path,
        ]);
        return redirect()->route('users.index', \Auth::user());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        return view('posts.show', [
            'title' => '投稿詳細',
            'post' => $post,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);
        return view('posts.edit', [
            'title' => '投稿編集',
            'post' => $post,
            'category_ids' => Category::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, PostRequest $request)
    {
        $post = Post::find($id);
        $post->update($request->only(['category_id', 'title', 'article']));
        session()->flash('success', '投稿を編集しました');
        return redirect()->route('posts.show', $post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        if($post->image !== ''){
            \Storage::disk('public')->delete($post->image);
        }
        $post->delete();
        \Session::flash('success', '投稿を削除しました');
        return redirect()->route('users.index');
    }
    public function updateImage($id, PostRequest $request, FileUploadService $service){
        $path = $service->saveImage($request->file('image'));
        $post = Post::find($id);
        if($post->image !== ''){
            \Storage::disk('public')->delete(\Storage::url($post->image));
        }
        $post->update(['image' => $path]);
        session()->flash('success', '投稿画像を変更しました');
        return redirect()->route('post.show', $post);
    }
    public function toggle_like($id){
        $user = \Auth::user();
        $post = Post::find($id);
        if($post->isLikedBy($user)){
            $post->likes->where('user_id', $user->id)->first()->delete();
            \Session::flash('success', 'いいねをとりけしました');
        }else{
            Like::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
            ]);
            \Session::flash('success', 'いいねしました');
        }
        return redirect()->route('top');
    }
}
