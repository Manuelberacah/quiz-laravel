<?php

namespace App\Handlers\Questions;

use App\Handlers\QuestionHandler;
use App\Models\Question;

class TextInputHandler implements QuestionHandler
{
    public function evaluate(Question $question, $userAnswer): array
    {
        // For text input, we compare case-insensitively with the first option's content
        $correctOption = $question->options()->first();
        
        $isCorrect = $correctOption && 
                     strtolower(trim($userAnswer)) === strtolower(trim($correctOption->content));
        
        return [
            'is_correct' => $isCorrect,
            'marks' => $isCorrect ? $question->marks : 0
        ];
    }
}
