@extends('layouts.app')

@section('content')
<div class="animate-fade-in">
    <div style="margin-bottom: 3rem;">
        <h1 style="font-size: 2.5rem;">Available Quizzes</h1>
        <p style="color: var(--text-muted);">Test your knowledge with our range of dynamic quizzes.</p>
    </div>

    @if($quizzes->isEmpty())
        <div class="card" style="text-align: center; padding: 4rem 2rem;">
            <p style="color: var(--text-muted); margin-bottom: 1.5rem;">No quizzes available yet. Be the first to create one!</p>
            <a href="{{ route('admin.quizzes.create') }}" class="btn btn-primary">Create Quiz</a>
        </div>
    @else
        <div class="grid">
            @foreach($quizzes as $quiz)
                <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; transition: transform 0.2s; cursor: default;">
                    <div>
                        <div class="badge" style="margin-bottom: 1rem;">{{ $quiz->questions_count }} Questions</div>
                        <h3 style="font-size: 1.25rem;">{{ $quiz->title }}</h3>
                        <p style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 1.5rem;">
                            {{ Str::limit($quiz->description, 100) }}
                        </p>
                    </div>
                    <a href="{{ route('quizzes.show', $quiz) }}" class="btn btn-primary" style="width: 100%; justify-content: center;">Start Quiz</a>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
