<?php

namespace Kapi\Models;

use Database\Factories\ContactFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Contact extends Model
{
    use HasFactory;

    /**
     * hidden
     *
     * @var array
     */
    protected $hidden = ['user_id'];

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'landline',
        'mobile',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return ContactFactory::new();
    }

    /**
     * user
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
