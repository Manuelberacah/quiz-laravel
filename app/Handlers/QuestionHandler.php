<?php

namespace App\Handlers;

use App\Models\Question;

interface QuestionHandler
{
    /**
     * Evaluate the user's answer.
     * 
     * @param Question $question
     * @param mixed $userAnswer
     * @return array{is_correct: bool, marks: int}
     */
    public function evaluate(Question $question, $userAnswer): array;
}
