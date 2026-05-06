@extends('layouts.app')

@section('content')
<div class="animate-fade-in">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem;">
        <div>
            <h1 style="font-size: 2rem;">Quiz Management</h1>
            <p style="color: var(--text-muted);">Create and manage your quizzes.</p>
        </div>
        <a href="{{ route('admin.quizzes.create') }}" class="btn btn-primary">Create New Quiz</a>
    </div>

    <div class="card" style="padding: 0; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f1f5f9; border-bottom: 1px solid var(--border);">
                    <th style="padding: 1rem 1.5rem; font-weight: 600;">Title</th>
                    <th style="padding: 1rem 1.5rem; font-weight: 600;">Questions</th>
                    <th style="padding: 1rem 1.5rem; font-weight: 600;">Attempts</th>
                    <th style="padding: 1rem 1.5rem; font-weight: 600; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quizzes as $quiz)
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 1rem 1.5rem;">
                            <div style="font-weight: 600;">{{ $quiz->title }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ Str::limit($quiz->description, 50) }}</div>
                        </td>
                        <td style="padding: 1rem 1.5rem;">{{ $quiz->questions()->count() }}</td>
                        <td style="padding: 1rem 1.5rem;">{{ $quiz->attempts()->count() }}</td>
                        <td style="padding: 1rem 1.5rem; text-align: right;">
                            <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                <form action="{{ route('admin.quizzes.destroy', $quiz) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline" style="color: var(--danger); border-color: #fee2e2;">Delete</button>
                                </form>
                                <a href="{{ route('quizzes.show', $quiz) }}" class="btn btn-outline">Preview</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding: 3rem; text-align: center; color: var(--text-muted);">No quizzes found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
