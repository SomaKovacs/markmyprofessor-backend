<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rating extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rating';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rating_message',
        'presentation',
        'interactive_tool_usage',
        'helpfulness',
        'preparation_level',
        'subject_utility',
        'requirement_difficulty',
        'subject',
        'teacher',
        'author'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'updated_at' => 'datetime:U',
        'created_at' => 'datetime:U',
    ];
}
