<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StandupQuestion extends Model
{
    protected $fillable = ['workspace_id', 'question', 'order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(StandupAnswer::class, 'question_id');
    }
}
