<?php

namespace App\Handlers\Questions;

use App\Handlers\QuestionHandler;
use App\Models\Question;

class NumberInputHandler implements QuestionHandler
{
    public function evaluate(Question $question, $userAnswer): array
    {
        // For number input, the correct answer is stored in the options table (first option content)
        $correctOption = $question->options()->first();
        
        $isCorrect = $correctOption && (float)$userAnswer === (float)$correctOption->content;
        
        return [
            'is_correct' => $isCorrect,
            'marks' => $isCorrect ? $question->marks : 0
        ];
    }
}
