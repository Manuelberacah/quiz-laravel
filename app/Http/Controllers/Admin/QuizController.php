<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::latest()->get();
        return view('admin.quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        return view('admin.quizzes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'questions' => 'required|array|min:1',
        ]);

        $quiz = Quiz::create($request->only('title', 'description'));

        foreach ($request->input('questions') as $index => $qData) {
            $question = $quiz->questions()->create([
                'type' => $qData['type'],
                'content' => $qData['content'],
                'video_url' => $qData['video_url'] ?? null,
                'marks' => $qData['marks'] ?? 1,
            ]);

            // Handle Image Upload for Question
            if ($request->hasFile("questions.{$index}.image")) {
                $path = $request->file("questions.{$index}.image")->store('media', 'public');
                $question->update(['image_path' => $path]);
            }

            if (isset($qData['options'])) {
                foreach ($qData['options'] as $oIndex => $oData) {
                    $option = $question->options()->create([
                        'content' => $oData['content'] ?? null,
                        'is_correct' => isset($oData['is_correct']),
                    ]);

                    // Handle Image Upload for Option
                    if ($request->hasFile("questions.{$index}.options.{$oIndex}.image")) {
                        $path = $request->file("questions.{$index}.options.{$oIndex}.image")->store('media', 'public');
                        $option->update(['image_path' => $path]);
                    }
                }
            }
        }

        return redirect()->route('admin.quizzes.index')->with('success', 'Quiz created successfully!');
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return back()->with('success', 'Quiz deleted successfully!');
    }
}
