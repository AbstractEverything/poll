<?php

namespace AbstractEverything\Poll\Models;

use AbstractEverything\Poll\Models\Poll;
use AbstractEverything\Poll\Models\Vote;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'poll_id',
        'label',
    ];

    /**
     * Poll relation
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }

    /**
     * Votes relation
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * Votes count
     * 
     * @return integer
     */
    public function votesCount()
    {
        return $this->hasOne(Vote::class)
            ->selectRaw('option_id, count(*) as count')
            ->groupBy('option_id');
    }

    /**
     * Percentage of total votes for this option
     * 
     * @param  integer $totalVotes
     * @return integer
     */
    public function votesPercent($totalVotes = 0)
    {
        $optionVotesCount = $this->votesCount['count'];

        if ($optionVotesCount == 0 && $totalVotes == 0)
        {
            return 0;
        }

        return round(($optionVotesCount / $totalVotes) * 100);
    }
}
