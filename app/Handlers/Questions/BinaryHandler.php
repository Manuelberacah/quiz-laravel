<?php

namespace App\Handlers\Questions;

use App\Handlers\QuestionHandler;
use App\Models\Question;

class BinaryHandler implements QuestionHandler
{
    public function evaluate(Question $question, $userAnswer): array
    {
        // userAnswer is the ID of the selected option
        $correctOption = $question->options()->where('is_correct', true)->first();
        
        $isCorrect = $correctOption && (int)$userAnswer === $correctOption->id;
        
        return [
            'is_correct' => $isCorrect,
            'marks' => $isCorrect ? $question->marks : 0
        ];
    }
}
