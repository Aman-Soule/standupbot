<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StandupAnswer extends Model
{

    protected $fillable = ['standup_id', 'question_id', 'answer'];

    public function standup(): BelongsTo
    {
        return $this->belongsTo(Standup::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(StandupQuestion::class, 'question_id');
    }
}
