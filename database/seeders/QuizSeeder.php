<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        $quiz = Quiz::create([
            'title' => 'General Knowledge Challenge',
            'description' => 'A comprehensive quiz testing various knowledge areas with different question formats.',
        ]);

        // 1. Binary Question
        $q1 = $quiz->questions()->create([
            'type' => 'binary',
            'content' => 'Is the Great Wall of China visible from space with the naked eye?',
            'marks' => 1,
        ]);
        $q1->options()->create(['content' => 'Yes', 'is_correct' => false]);
        $q1->options()->create(['content' => 'No', 'is_correct' => true]);

        // 2. Single Choice
        $q2 = $quiz->questions()->create([
            'type' => 'single',
            'content' => 'Which planet is known as the Red Planet?',
            'marks' => 1,
        ]);
        $q2->options()->create(['content' => 'Venus', 'is_correct' => false]);
        $q2->options()->create(['content' => 'Mars', 'is_correct' => true]);
        $q2->options()->create(['content' => 'Jupiter', 'is_correct' => false]);
        $q2->options()->create(['content' => 'Saturn', 'is_correct' => false]);

        // 3. Multiple Choice
        $q3 = $quiz->questions()->create([
            'type' => 'multiple',
            'content' => 'Which of the following are primary colors?',
            'marks' => 2,
        ]);
        $q3->options()->create(['content' => 'Red', 'is_correct' => true]);
        $q3->options()->create(['content' => 'Green', 'is_correct' => false]);
        $q3->options()->create(['content' => 'Blue', 'is_correct' => true]);
        $q3->options()->create(['content' => 'Yellow', 'is_correct' => true]);

        // 4. Number Input
        $q4 = $quiz->questions()->create([
            'type' => 'number',
            'content' => 'How many continents are there on Earth?',
            'marks' => 1,
        ]);
        $q4->options()->create(['content' => '7', 'is_correct' => true]);

        // 5. Text Input
        $q5 = $quiz->questions()->create([
            'type' => 'text',
            'content' => 'Who wrote the play "Romeo and Juliet"?',
            'marks' => 1,
        ]);
        $q5->options()->create(['content' => 'William Shakespeare', 'is_correct' => true]);

        // 6. Media Question (Image)
        $q6 = $quiz->questions()->create([
            'type' => 'single',
            'content' => 'Identify the creator of this quiz system architecture.',
            'marks' => 1,
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', // Just for testing video
        ]);
        $q6->options()->create(['content' => 'AI Assistant', 'is_correct' => true]);
        $q6->options()->create(['content' => 'Human Developer', 'is_correct' => false]);
    }
}
