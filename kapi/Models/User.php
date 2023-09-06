<?php

namespace Kapi\Models;

use Database\Factories\UserFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'job_title_id',
        'first_name',
        'last_name',
        'password',
        'dob',
        'api_key',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * with
     *
     * @var array
     */
    //protected $with = ['contacts'];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return UserFactory::new();
    }

    /**
     * jobTitle
     *
     * @return void
     */
    public function jobTitle()
    {
        return $this->belongsTo(JobTitle::class);
    }
    
    /**
     * contact
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function contact()
    {
        return $this->hasOne(Contact::class)->select(['id', 'user_id', 'landline', 'mobile']);
    }

    /**
     * Scope a query to return user with given api key
     */
    public function scopeApiKey(Builder $query, string $api_key): Builder
    {
        return $query->where('api_key', $api_key);
    }
    
    /**
     * scopeWithContact
     *
     * @return void
     */
    public function scopeWithContact(Builder $query)
    {
        $query->with('contact');
    }
    
    /**
     * Scope a query to return active users only
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', 1);
    }

    /**
     * Scope a query to return inactive users only
     */
    public function scopeInActive(Builder $query): Builder
    {
        return $query->where('active', 0);
    }

    /**
     * Scope a query to return based on terms
     *
     * @param Builder $query
     * @param string|null $terms
     * @return void
     */
    public function scopeSearch(Builder $query, string $terms = null)
    {
        collect(str_getcsv($terms, ' ', '"'))->filter()->each(function ($term) use ($query) {
            $term = preg_replace('/[^A-Za-z0-9]/', '', $term).'%';
            $query->where(function ($query) use ($term) {
                $query->where('full_name', 'like', $term)
                    ->orWhere('email', 'like', $term)
                    ->orWhereHas('jobTitle', function ($query) use ($term) {
                        $query->where('job_title', 'like', $term);
                    });
            });
        });
    }
}
