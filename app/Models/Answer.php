<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = ['attempt_id', 'question_id', 'answer_text', 'option_ids', 'is_correct', 'marks_earned'];

    protected $casts = [
        'option_ids' => 'json',
        'is_correct' => 'boolean',
    ];

    public function attempt()
    {
        return $this->belongsTo(Attempt::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
