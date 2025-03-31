<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Idea extends Model
{
    protected $fillable = ['title', 'description', 'user_id'];

    public function user(){
    return $this->belongsTo(User::class);
}

    public function comments(){
    return $this->hasMany(Comment::class);
}

    public function upvotes(){
    return $this->hasMany(Upvote::class);
}
    public function bookmarkedBy(){
    return $this->belongsToMany(User::class, 'bookmarks')->withTimestamps();
}



}
