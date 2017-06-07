<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $guarded = [];

    public static function upvote(Post $post)
    {
        static::addVote($post, 1);
    }

    public static function downvote(Post $post)
    {
        static::addVote($post, -1);
    }

    protected static function addVote(Post $post, $amount)
    {
        static::updateOrCreate(
            ['post_id' => $post->id, 'user_id' => auth()->id()],
            ['vote' => $amount]
        );

        static::refreshPostScore($post);
    }

    public static function undoVote(Post $post)
    {
        static::where([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
        ])->delete();

        static::refreshPostScore($post);
    }

    protected static function refreshPostScore(Post $post)
    {
        $post->score = static::where(['post_id' => $post->id])->sum('vote');

        $post->save();
    }
}
