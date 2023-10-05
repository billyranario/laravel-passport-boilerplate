<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'author_id',
        'activity',
        'type',
    ];

    /**
     * @return BelongsTo
     */
    public function loggedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
