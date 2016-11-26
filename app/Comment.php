<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['comment', 'post_id'];

    protected $casts = [
        'answer' => 'boolean'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function markAsAnswer()
    {
        $this->post->comments()->where('answer', true)->update(['answer' => false]);

        $this->answer = true;

        $this->save();

        $this->post->pending = false;

        $this->post->save();
    }
}
