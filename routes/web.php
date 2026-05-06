<?php

use App\Http\Controllers\QuizController;
use App\Http\Controllers\Admin\QuizController as AdminQuizController;

Route::get('/', [QuizController::class, 'index'])->name('quizzes.index');
Route::get('/quiz/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
Route::post('/quiz/{quiz}/attempt', [QuizController::class, 'submit'])->name('quizzes.submit');
Route::get('/attempt/{attempt}', [QuizController::class, 'result'])->name('quizzes.result');

Route::prefix('admin')->name('admin.')->group(function() {
    Route::resource('quizzes', AdminQuizController::class);
});
