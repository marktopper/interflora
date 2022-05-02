<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Node extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'parent_id',
        'department',
        'programming_language',
    ];

    protected $appends = [
        'height',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Node::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Node::class, 'parent_id');
    }

    public function allParents(): Collection
    {
        if (is_null($this->parent)) {
            return collect([]);
        }

        return collect(array_merge([$this->parent], $this->parent->allParents()->toArray()));
    }

    public function getHeightAttribute(): int
    {
        return count($this->allParents());
    }
}
