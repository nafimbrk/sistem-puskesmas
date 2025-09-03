<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Queue extends Model
{
    protected $guarded = ['id'];

    /**
     * Get all of the comments for the Queue
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function prescription(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }

    /**
     * Get the user that owns the Queue
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function poli(): BelongsTo
    {
        return $this->belongsTo(Poli::class);
    }
}
