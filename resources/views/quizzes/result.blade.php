@extends('layouts.app')

@section('content')
<div class="animate-fade-in" style="max-width: 800px; margin: 0 auto;">
    <div class="card" style="text-align: center; padding: 4rem 2rem; border-top: 8px solid var(--primary);">
        <h1 style="font-size: 1.5rem; color: var(--text-muted); margin-bottom: 0.5rem;">QUIZ COMPLETED</h1>
        <h2 style="font-size: 2.5rem; margin-bottom: 2rem;">{{ $attempt->quiz->title }}</h2>
        
        <div style="display: inline-block; padding: 2rem; border-radius: 50%; background: #f5f3ff; border: 4px solid var(--primary); margin-bottom: 2rem;">
            <div style="font-size: 3rem; font-weight: 800; color: var(--primary);">{{ $attempt->total_score }}</div>
            <div style="font-weight: 600; color: var(--text-muted);">TOTAL SCORE</div>
        </div>

        <p style="color: var(--text-muted); margin-bottom: 2rem;">
            Attempted on {{ $attempt->completed_at->format('M d, Y \a\t H:i') }}
        </p>

        <div style="display: flex; gap: 1rem; justify-content: center;">
            <a href="{{ route('quizzes.show', $attempt->quiz) }}" class="btn btn-outline">Try Again</a>
            <a href="{{ route('quizzes.index') }}" class="btn btn-primary">Back to Home</a>
        </div>
    </div>

    <h3 style="margin: 3rem 0 1.5rem;">Review Answers</h3>
    @foreach($attempt->answers as $index => $answer)
        <div class="card" style="margin-bottom: 1.5rem; border-left: 4px solid {{ $answer->is_correct ? 'var(--success)' : 'var(--danger)' }};">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <span style="font-weight: 700; color: var(--text-muted);">QUESTION {{ $index + 1 }}</span>
                <span class="badge" style="background: {{ $answer->is_correct ? '#d1fae5' : '#fee2e2' }}; color: {{ $answer->is_correct ? '#065f46' : '#991b1b' }};">
                    {{ $answer->marks_earned }} / {{ $answer->question->marks }} Marks
                </span>
            </div>
            
            <div style="font-weight: 500; margin-bottom: 1rem;">{!! $answer->question->content !!}</div>
            
            <div style="font-size: 0.875rem;">
                <div style="margin-bottom: 0.5rem;">
                    <strong>Your Answer:</strong> 
                    @if($answer->question->type === 'multiple')
                        @php
                            $selectedOptions = $answer->question->options->whereIn('id', $answer->option_ids ?? []);
                        @endphp
                        {{ $selectedOptions->pluck('content')->implode(', ') ?: 'No answer' }}
                    @elseif($answer->question->type === 'single' || $answer->question->type === 'binary')
                        @php
                            $selectedOption = $answer->question->options->where('id', $answer->answer_text)->first();
                        @endphp
                        {{ $selectedOption ? $selectedOption->content : 'No answer' }}
                    @else
                        {{ $answer->answer_text ?: 'No answer' }}
                    @endif
                </div>

                @if(!$answer->is_correct)
                    <div style="color: var(--success); font-weight: 600;">
                        <strong>Correct Answer:</strong>
                        @if($answer->question->type === 'multiple')
                            {{ $answer->question->options->where('is_correct', true)->pluck('content')->implode(', ') }}
                        @elseif($answer->question->type === 'single' || $answer->question->type === 'binary')
                            {{ $answer->question->options->where('is_correct', true)->first()->content ?? 'N/A' }}
                        @else
                            {{ $answer->question->options->first()->content ?? 'N/A' }}
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
