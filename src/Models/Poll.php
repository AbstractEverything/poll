<?php

namespace AbstractEverything\Poll\Models;

use AbstractEverything\Poll\Models\Option;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'multivote',
        'closed',
        'ends_at',
    ];

    /**
     * Options relation
     * 
     * @return lluminate\Database\Eloquent\Relations\HasMany
     */
    public function options()
    {
        return $this->hasMany(Option::class);
    }

    /**
     * Number of options for ths poll
     * 
     * @return integer
     */
    public function optionsCount()
    {
        return $this->options()->count();
    }

    /**
     * Votes relation
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function votes()
    {
        return $this->hasManyThrough(Vote::class, Option::class);
    }

    /**
     * Total votes for this poll
     * 
     * @return integer
     */
    public function totalVotes()
    {
        return $this->votes()->count();
    }

    /**
     * Has this user id already voted in this poll
     * 
     * @param  integer  $userId
     * @return boolean
     */
    public function hasVoted($userId)
    {
        return $this->votes()->where('user_id', $userId)->count() > 0;
    }
}
