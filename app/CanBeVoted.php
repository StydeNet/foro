<?php

namespace App;

use Collective\Html\HtmlFacade as Html;

trait CanBeVoted
{
    public function votes()
    {
        return $this->morphMany(Vote::class, 'votable');
    }

    public function userVote()
    {
        return $this->morphOne(Vote::class, 'votable')
            ->where('user_id', auth()->id())
            ->withDefault();
    }

    public function getCurrentVoteAttribute()
    {
        if (auth()->check()) {
            return $this->userVote->vote;
        }
    }

    public function getVoteFrom(User $user)
    {
        return $this->votes()
            ->where('user_id', $user->id)
            ->value('vote');
    }

    /**
     * Renders the Vue vote component.
     *
     * @return string
     */
    public function getVoteComponentAttribute()
    {
        if (auth()->check()) {
            return Html::tag('app-vote', '', [
                'module' => $this->getTable(), // posts or comments
                'id' => $this->id,
                'score' => $this->score,
                'vote' => $this->current_vote
            ]);
        }
    }
    
    public function upvote()
    {
        $this->addVote(1);
    }

    public function downvote()
    {
        $this->addVote(-1);
    }

    protected function addVote($amount)
    {
        $this->votes()->updateOrCreate(
            ['user_id' => auth()->id()],
            ['vote' => $amount]
        );

        $this->refreshPostScore();
    }

    public function undoVote()
    {
        $this->votes()
            ->where('user_id', auth()->id())
            ->delete();

        $this->refreshPostScore();
    }

    protected function refreshPostScore()
    {
        $this->score = $this->votes()->sum('vote');

        $this->save();
    }
}
