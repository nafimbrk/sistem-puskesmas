<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    protected $guarded = ['id'];

    /**
     * Get all of the comments for the Patient
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function queue(): HasMany
    {
        return $this->hasMany(Queue::class);
    }
}
