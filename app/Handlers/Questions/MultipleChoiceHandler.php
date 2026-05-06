<?php

namespace App\Handlers\Questions;

use App\Handlers\QuestionHandler;
use App\Models\Question;

class MultipleChoiceHandler implements QuestionHandler
{
    public function evaluate(Question $question, $userAnswer): array
    {
        // userAnswer is an array of option IDs
        $correctOptionIds = $question->options()->where('is_correct', true)->pluck('id')->toArray();
        
        $userAnswer = is_array($userAnswer) ? $userAnswer : [$userAnswer];
        $userAnswer = array_map('intval', $userAnswer);

        // Check if user's answers exactly match correct answers (order doesn't matter)
        sort($correctOptionIds);
        sort($userAnswer);

        $isCorrect = $correctOptionIds === $userAnswer;
        
        return [
            'is_correct' => $isCorrect,
            'marks' => $isCorrect ? $question->marks : 0
        ];
    }
}
