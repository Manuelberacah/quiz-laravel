<?php

namespace App\Services;

use App\Models\Attempt;
use App\Handlers\Questions\BinaryHandler;
use App\Handlers\Questions\SingleChoiceHandler;
use App\Handlers\Questions\MultipleChoiceHandler;
use App\Handlers\Questions\NumberInputHandler;
use App\Handlers\Questions\TextInputHandler;
use Exception;

class QuizEvaluator
{
    protected $handlers = [
        'binary' => BinaryHandler::class,
        'single' => SingleChoiceHandler::class,
        'multiple' => MultipleChoiceHandler::class,
        'number' => NumberInputHandler::class,
        'text' => TextInputHandler::class,
    ];

    public function evaluate(Attempt $attempt)
    {
        $totalScore = 0;

        foreach ($attempt->answers as $answer) {
            $question = $answer->question;
            $handlerClass = $this->handlers[$question->type] ?? null;

            if (!$handlerClass) {
                throw new Exception("No handler found for question type: {$question->type}");
            }

            $handler = new $handlerClass();
            
            // For multiple choice, we pass option_ids. For others, answer_text.
            $userResponse = ($question->type === 'multiple') ? $answer->option_ids : 
                           (($question->type === 'single' || $question->type === 'binary') ? $answer->answer_text : $answer->answer_text);

            $result = $handler->evaluate($question, $userResponse);

            $answer->update([
                'is_correct' => $result['is_correct'],
                'marks_earned' => $result['marks'],
            ]);

            $totalScore += $result['marks'];
        }

        $attempt->update([
            'total_score' => $totalScore,
            'completed_at' => now(),
        ]);

        return $attempt;
    }
}
