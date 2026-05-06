<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Attempt;
use App\Models\Answer;
use App\Services\QuizEvaluator;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::withCount('questions')->get();
        return view('quizzes.index', compact('quizzes'));
    }

    public function show(Quiz $quiz)
    {
        $quiz->load('questions.options');
        return view('quizzes.show', compact('quiz'));
    }

    public function submit(Request $request, Quiz $quiz, QuizEvaluator $evaluator)
    {
        $attempt = Attempt::create([
            'quiz_id' => $quiz->id,
            'total_score' => 0,
        ]);

        foreach ($quiz->questions as $question) {
            $response = $request->input("question_{$question->id}");
            
            $answerData = [
                'attempt_id' => $attempt->id,
                'question_id' => $question->id,
            ];

            if ($question->type === 'multiple') {
                $answerData['option_ids'] = $response ?? [];
            } else {
                $answerData['answer_text'] = $response;
            }

            Answer::create($answerData);
        }

        $evaluator->evaluate($attempt);

        return redirect()->route('quizzes.result', $attempt->id);
    }

    public function result(Attempt $attempt)
    {
        $attempt->load(['quiz', 'answers.question.options']);
        return view('quizzes.result', compact('attempt'));
    }
}
