<?php

namespace App\Handlers\Questions;

use App\Handlers\QuestionHandler;
use App\Models\Question;

class SingleChoiceHandler implements QuestionHandler
{
    public function evaluate(Question $question, $userAnswer): array
    {
        $correctOption = $question->options()->where('is_correct', true)->first();
        
        $isCorrect = $correctOption && (int)$userAnswer === $correctOption->id;
        
        return [
            'is_correct' => $isCorrect,
            'marks' => $isCorrect ? $question->marks : 0
        ];
    }
}
