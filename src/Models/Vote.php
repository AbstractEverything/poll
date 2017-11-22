<?php

namespace AbstractEverything\Poll\Models;

use AbstractEverything\Poll\Models\Option;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'option_id',
    ];

    /**
     * Option relation
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function option()
    {
        return $this->belongsTo(Option::class);
    }
}
