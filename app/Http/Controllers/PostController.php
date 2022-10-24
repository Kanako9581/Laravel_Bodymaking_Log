<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Postrequest;
use App\User;
use App\Post;
use App\PostImage;
use App\Like;
use App\Follow;
use App\Category;
use App\Tag;
use App\PostTag;
use App\Http\Service\FileUploadService;

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
    public function index(Request $request)
    {
        $user = \Auth::user();
        $keyword = $request->input('keyword');
        $query = Post::query();
        if($keyword){
            $space_conversion = mb_convert_kana($keyword, 's');
            $keyword_array_searched = preg_split('/[\s,]+/', $space_conversion);
            foreach($keyword_array_searched as $value){
                $query->where('article', 'like', '%'.$value.'%')->orWhere('title', 'like', '%'.$value.'%')
                ->orWhere('post_tags_string', 'like', '%'.$value.'%');
            }
            $posts = $query->paginate(5);
        }else{
            $follow_user_ids = $user->follow_users->pluck('id');
            $posts = $user->posts()->orWhereIn('user_id', $follow_user_ids)->latest()->get();
        }
        return view('top', [
            'title' => 'みんなの投稿',
            'user' => $user,
            'posts' => $posts,
            'keyword' => $keyword,
            'recommended_users' => User::recommend($user->id)->get(),
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
        // $path = $service->saveImage($request->file('image'));
        $post = Post::create([
            'user_id' => \Auth::user()->id,
            'name' => \Auth::user()->name,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'article' => $request->article,
            // 'image' => $path,
            'post_tags_string' => $request->post_tags_string,
        ]);
        $post_tags = $post->post_tags_string;
        // $tags = $request->input('tag');
        if($post_tags){
            $space_conversion = mb_convert_kana($post_tags, 's');//tagsのスペースを半角にする
            $tags_array = preg_split("/[\s,]+/", $space_conversion);
            // $tags_array = preg_split('/[\s,]+/', $space_conversion, -1, 'PREG_SPRIT_NO_EMPTY');スペースで区切ってタグを配列にする
            // dd($tags_array);
            foreach($tags_array as $tag){
                $tag = Tag::firstOrCreate([
                    'tag' => $tag,
                ]);
                PostTag::create([
                    'post_id' => $post->id,
                    'tag_id' => $tag->id,
                ]);
            }
        }
        // foreach($tags as $tag){
        //     if(Tag::where('id', $request->id)->exists()){
        //         $tag = Tag::find($id);
        //     }else{
        //         Tag::create([
        //             'post_id' => $post->id,
        //             'tag' => $request->tag,
        //         ]);
        //         $tag = Tag::find($id);
        //         PostTag::create([
        //             'post_id' => $post->id,
        //             'tag_id' => $tag->id,
        //         ]);
        //     }
        // }
        // $images = $request->images;
        // foreach($images as $image){
        //     PostImage::create([
        //         'image'=>$image->path,
        //         'post_id'=>$post->id
        //     ]);
        // }
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
        $user = \Auth::user();
        $post_tags = $post->tags()->get();
        return view('posts.show', [
            'title' => '投稿詳細',
            'post' => $post,
            'user' => $user,
            'post_tags' => $post_tags,
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
        $post->update($request->only(['category_id', 'title', 'article', 'post_tags_string']));
        $post->tags()->delete();
        if($post->post_tags_string){
            $space_conversion = mb_convert_kana($post->post_tags_string, 's');//tagsのスペースを半角にする
            $tags_array = preg_split("/[\s,]+/", $space_conversion);
            foreach($tags_array as $tag){
                $tag = Tag::firstOrCreate([
                    'tag' => $tag,
                ]);
                PostTag::create([
                    'post_id' => $post->id,
                    'tag_id' => $tag->id,
                ]);
            }
        }
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
    public function tag($id){
        $user = \Auth::user();
        $tag = Tag::find($id);
        $tag_posts = $tag->posts()->latest()->get();
        return view('posts.tag', [
            'title' => 'タグ検索結果: ',
            'user' => $user,
            'tag' => $tag,
            'tag_posts' => $tag_posts,
        ]);
    }
    // public function search(Request $request){
    //     $keyword = $request->input('keyword');
    //     $query = Post::query();
    //     if($keyword){
    //         $space_conversion = mb_convert_kana($keyword, 's');
    //         $keyword_array_searched = preg_split('/[\s,]+/', $space_conversion, -1, PREG_SPRIT_NO_EMPTY);
    //         foreach($keyword_array_searched as $value){
    //             $query->where('article', 'like', '%'.$value.'%');
    //         }
    //         $posts = $query->paginate(5);
    //     }else{
    //         $user = \Auth::user();
    //         $follow_user_ids = $user->follow_users->pluck('id');
    //         $posts = $user->posts()->orWhereIn('user_id', $follow_user_ids)->latest()->get();
    //     }
    //     return view('posts.search', [
    //         'title' => '検索結果',
    //         'posts' => $posts,
    //         'keyword' => $keyword,
    //     ]);
    // }
}
