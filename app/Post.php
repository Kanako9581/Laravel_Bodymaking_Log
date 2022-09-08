<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['user_id', 'article', 'category_id', 'comment', 'image'];
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function comments(){
        return $this->hasMany('App\Comment');
    }
    public function likes(){
        return $this->hasMany('App\Like');
    }
    public function likedUsers(){
        return $this->belongsToMany('App\User', 'likes');
    }
    public function isLikedBy($user){
        $liked_users_ids = $this->likedUsers->pluck('id');
        $result = $liked_users_ids->contains($user->id);
        return $result;
    }
    public function category(){
        return $this->belongsTo('App\Category');
    }

}
