<?php

namespace Kapi\Models;

use Database\Factories\JobTitleFactory;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobTitle extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'job_title',
    ];
    
    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return JobTitleFactory::new();
    }

    /**
     * Scope a query to return job_title equals
     *
     * @param Builder $query
     * @param string $job_title
     * @return Builder
     */
    public function scopeJobTitle(Builder $query, string $job_title): Builder
    {
        return $query->where('job_title', $job_title);
    }
}
