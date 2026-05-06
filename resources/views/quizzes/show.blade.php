@extends('layouts.app')

@section('content')
<div class="animate-fade-in" style="max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 3rem; text-align: center;">
        <h1 style="font-size: 2rem;">{{ $quiz->title }}</h1>
        <p style="color: var(--text-muted);">{{ $quiz->description }}</p>
    </div>

    <form action="{{ route('quizzes.submit', $quiz) }}" method="POST">
        @csrf
        @foreach($quiz->questions as $index => $question)
            <div class="card" style="margin-bottom: 3rem; border-left: 4px solid var(--primary);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
                    <span style="font-weight: 700; color: var(--primary); font-size: 0.875rem;">QUESTION {{ $index + 1 }}</span>
                    <span class="badge">{{ $question->marks }} Marks</span>
                </div>

                <div style="font-size: 1.125rem; font-weight: 500; margin-bottom: 1.5rem;">
                    {!! $question->content !!}
                </div>

                @if($question->image_path)
                    <div class="media-container">
                        <img src="{{ asset('storage/' . $question->image_path) }}" alt="Question Image">
                    </div>
                @endif

                @if($question->video_url)
                    @php
                        $videoId = '';
                        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $question->video_url, $match)) {
                            $videoId = $match[1];
                        }
                    @endphp
                    @if($videoId)
                        <div class="media-container">
                            <div class="video-wrapper">
                                <iframe src="https://www.youtube.com/embed/{{ $videoId }}" frameborder="0" allowfullscreen></iframe>
                            </div>
                        </div>
                    @endif
                @endif

                <div style="margin-top: 2rem;">
                    @if($question->type === 'binary')
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            @foreach($question->options as $option)
                                <label style="display: block; cursor: pointer;">
                                    <input type="radio" name="question_{{ $question->id }}" value="{{ $option->id }}" style="display: none;" class="peer">
                                    <div style="padding: 1rem; border: 2px solid var(--border); border-radius: 0.75rem; text-align: center; transition: all 0.2s;" class="option-card">
                                        @if($option->image_path)
                                            <img src="{{ asset('storage/' . $option->image_path) }}" style="max-height: 100px; margin-bottom: 0.5rem; border-radius: 0.25rem;">
                                        @endif
                                        <div>{{ $option->content }}</div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @elseif($question->type === 'single')
                        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                            @foreach($question->options as $option)
                                <label style="display: flex; align-items: center; gap: 1rem; padding: 1rem; border: 2px solid var(--border); border-radius: 0.75rem; cursor: pointer;" class="option-card">
                                    <input type="radio" name="question_{{ $question->id }}" value="{{ $option->id }}" required>
                                    @if($option->image_path)
                                        <img src="{{ asset('storage/' . $option->image_path) }}" style="max-height: 50px; border-radius: 0.25rem;">
                                    @endif
                                    <span>{{ $option->content }}</span>
                                </label>
                            @endforeach
                        </div>
                    @elseif($question->type === 'multiple')
                        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                            @foreach($question->options as $option)
                                <label style="display: flex; align-items: center; gap: 1rem; padding: 1rem; border: 2px solid var(--border); border-radius: 0.75rem; cursor: pointer;" class="option-card">
                                    <input type="checkbox" name="question_{{ $question->id }}[]" value="{{ $option->id }}">
                                    @if($option->image_path)
                                        <img src="{{ asset('storage/' . $option->image_path) }}" style="max-height: 50px; border-radius: 0.25rem;">
                                    @endif
                                    <span>{{ $option->content }}</span>
                                </label>
                            @endforeach
                        </div>
                    @elseif($question->type === 'number')
                        <input type="number" name="question_{{ $question->id }}" placeholder="Enter your numerical answer" required style="padding: 1rem; font-size: 1rem;">
                    @elseif($question->type === 'text')
                        <textarea name="question_{{ $question->id }}" rows="3" placeholder="Enter your text answer" required style="padding: 1rem; font-size: 1rem;"></textarea>
                    @endif
                </div>
            </div>
        @endforeach

        <div style="text-align: center; margin-top: 4rem;">
            <button type="submit" class="btn btn-primary" style="padding: 1rem 3rem; font-size: 1.125rem;">Submit Quiz</button>
        </div>
    </form>
</div>

<style>
    input[type="radio"]:checked + .option-card,
    input[type="checkbox"]:checked + .option-card {
        border-color: var(--primary) !important;
        background-color: #f5f3ff !important;
    }
    
    label:has(input:checked) {
        border-color: var(--primary) !important;
        background-color: #f5f3ff !important;
    }
</style>
@endsection
