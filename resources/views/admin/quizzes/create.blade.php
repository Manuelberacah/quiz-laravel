@extends('layouts.app')

@section('content')
<div class="animate-fade-in" style="max-width: 900px; margin: 0 auto;">
    <div style="margin-bottom: 3rem;">
        <h1 style="font-size: 2rem;">Create New Quiz</h1>
        <p style="color: var(--text-muted);">Configure your quiz details and add questions.</p>
    </div>

    <form action="{{ route('admin.quizzes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <h3 style="margin-bottom: 1.5rem;">Basic Information</h3>
            <div class="form-group">
                <label for="title">Quiz Title</label>
                <input type="text" name="title" id="title" placeholder="e.g. Science Basics" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" rows="3" placeholder="Describe what this quiz is about..."></textarea>
            </div>
        </div>

        <div id="questions-container">
            <!-- Questions will be added here -->
        </div>

        <div style="display: flex; gap: 1rem; margin-bottom: 3rem;">
            <button type="button" class="btn btn-outline" id="add-question" style="flex: 1; justify-content: center; border-style: dashed; border-width: 2px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 0.5rem;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Add Question
            </button>
        </div>

        <div style="text-align: right; border-top: 1px solid var(--border); padding-top: 2rem;">
            <a href="{{ route('admin.quizzes.index') }}" class="btn btn-outline" style="margin-right: 1rem;">Cancel</a>
            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2.5rem;">Save Quiz</button>
        </div>
    </form>
</div>

<!-- Question Template -->
<template id="question-template">
    <div class="card question-card" style="margin-bottom: 2rem; position: relative;" data-index="{index}">
        <button type="button" class="remove-question" style="position: absolute; top: 1rem; right: 1rem; background: none; border: none; color: var(--danger); cursor: pointer; font-weight: 600;">Remove</button>
        
        <div style="display: flex; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div style="flex: 2;">
                <label>Question Type</label>
                <select name="questions[{index}][type]" class="question-type-select" required>
                    <option value="binary">Binary (Yes/No)</option>
                    <option value="single" selected>Single Choice</option>
                    <option value="multiple">Multiple Choice</option>
                    <option value="number">Number Input</option>
                    <option value="text">Text Input</option>
                </select>
            </div>
            <div style="flex: 1;">
                <label>Marks</label>
                <input type="number" name="questions[{index}][marks]" value="1" min="1" required>
            </div>
        </div>

        <div class="form-group">
            <label>Question Content (HTML supported)</label>
            <textarea name="questions[{index}][content]" rows="2" required placeholder="Enter the question text..."></textarea>
        </div>

        <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
            <div style="flex: 1;">
                <label>Image (Optional)</label>
                <input type="file" name="questions[{index}][image]" accept="image/*">
            </div>
            <div style="flex: 1;">
                <label>Video URL (Optional YouTube)</label>
                <input type="text" name="questions[{index}][video_url]" placeholder="https://youtube.com/...">
            </div>
        </div>

        <div class="options-section">
            <label style="margin-bottom: 1rem; display: block; font-weight: 600;">Options / Correct Answer</label>
            <div class="options-container" style="display: flex; flex-direction: column; gap: 0.75rem;">
                <!-- Options will be added here -->
            </div>
            <button type="button" class="btn btn-outline btn-sm add-option" style="margin-top: 1rem; font-size: 0.75rem;">+ Add Option</button>
        </div>
    </div>
</template>

<!-- Option Template -->
<template id="option-template">
    <div class="option-row" style="display: flex; align-items: center; gap: 1rem;">
        <input type="{type}" name="questions[{qIndex}][options][{oIndex}][is_correct]" value="1" class="is-correct-input">
        <input type="text" name="questions[{qIndex}][options][{oIndex}][content]" placeholder="Option content" style="flex: 1;" required>
        <input type="file" name="questions[{qIndex}][options][{oIndex}][image]" style="width: 200px; font-size: 0.7rem;">
        <button type="button" class="remove-option" style="color: var(--danger); background: none; border: none; cursor: pointer;">&times;</button>
    </div>
</template>

@endsection

@section('scripts')
<script>
    let questionCount = 0;

    const container = document.getElementById('questions-container');
    const addBtn = document.getElementById('add-question');
    const qTemplate = document.getElementById('question-template').innerHTML;
    const oTemplate = document.getElementById('option-template').innerHTML;

    function addQuestion() {
        const index = questionCount++;
        const html = qTemplate.replace(/{index}/g, index);
        const div = document.createElement('div');
        div.innerHTML = html;
        container.appendChild(div.firstElementChild);
        
        const typeSelect = div.firstElementChild.querySelector('.question-type-select');
        updateOptionsSection(div.firstElementChild, index, typeSelect.value);

        typeSelect.addEventListener('change', (e) => {
            updateOptionsSection(div.firstElementChild, index, e.target.value);
        });
    }

    function updateOptionsSection(qCard, qIndex, type) {
        const optionsContainer = qCard.querySelector('.options-container');
        const addOptionBtn = qCard.querySelector('.add-option');
        optionsContainer.innerHTML = '';

        if (type === 'binary') {
            addOptionBtn.style.display = 'none';
            addOption(optionsContainer, qIndex, 0, 'radio', 'Yes');
            addOption(optionsContainer, qIndex, 1, 'radio', 'No');
        } else if (type === 'single') {
            addOptionBtn.style.display = 'inline-flex';
            addOption(optionsContainer, qIndex, 0, 'radio', 'Option 1');
            addOption(optionsContainer, qIndex, 1, 'radio', 'Option 2');
        } else if (type === 'multiple') {
            addOptionBtn.style.display = 'inline-flex';
            addOption(optionsContainer, qIndex, 0, 'checkbox', 'Option 1');
            addOption(optionsContainer, qIndex, 1, 'checkbox', 'Option 2');
        } else {
            addOptionBtn.style.display = 'none';
            addOption(optionsContainer, qIndex, 0, 'radio', '', 'Correct Answer');
        }
    }

    function addOption(container, qIndex, oIndex, inputType, defaultContent = '', placeholder = 'Option content') {
        const html = oTemplate
            .replace(/{qIndex}/g, qIndex)
            .replace(/{oIndex}/g, oIndex)
            .replace(/{type}/g, inputType);
        
        const div = document.createElement('div');
        div.innerHTML = html;
        const row = div.firstElementChild;
        if (defaultContent) row.querySelector('input[type="text"]').value = defaultContent;
        if (placeholder) row.querySelector('input[type="text"]').placeholder = placeholder;
        
        container.appendChild(row);
    }

    addBtn.addEventListener('click', addQuestion);

    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-question')) {
            e.target.closest('.question-card').remove();
        }
        if (e.target.classList.contains('remove-option')) {
            e.target.closest('.option-row').remove();
        }
        if (e.target.classList.contains('add-option')) {
            const qCard = e.target.closest('.question-card');
            const qIndex = qCard.dataset.index;
            const container = qCard.querySelector('.options-container');
            const oIndex = container.children.length;
            const type = qCard.querySelector('.question-type-select').value;
            addOption(container, qIndex, oIndex, type === 'multiple' ? 'checkbox' : 'radio');
        }
    });

    // Add first question by default
    addQuestion();
</script>
@endsection
