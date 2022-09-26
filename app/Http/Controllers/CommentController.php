<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use App\Comment;

class CommentController extends Controller
{
     public function store(CommentRequest $request){
        Comment::create([
            'post_id' => $request->post_id,
            'user_id' => \Auth::user()->id,
            'body' => $request->body,
        ]);
        \Session::flash('success', 'コメントを投稿しました');
        return redirect()->route('top');
    }
    public function destroy($id){
        $comment = Comment::find($id);
        $comment->delete();
        \Session::flash('success', 'コメントを削除しました');
        return redirect()->route('top');
    }
}
