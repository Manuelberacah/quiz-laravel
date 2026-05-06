# Architecture Documentation - Dynamic Quiz System

## Overview
The Dynamic Quiz System is designed with extensibility and maintainability as core priorities. It uses a **Strategy Pattern** for question evaluation, allowing new question types to be added without modifying the core logic.

## Core Design Patterns

### 1. Strategy Pattern (Question Handlers)
Each question type (Binary, Single Choice, Multiple Choice, Number, Text) is handled by a dedicated class implementing the `QuestionHandler` interface.
- **Why?** This avoids "god classes" or massive switch statements in the evaluation logic.
- **Location**: `App\Handlers\Questions\`

### 2. Service Layer
The `QuizEvaluator` service orchestrates the evaluation of a quiz attempt. It iterates through answers, delegates scoring to the appropriate `QuestionHandler`, and calculates the total score.

## Data Modeling

### Schema Diagram (Simplified)
- **Quizzes**: The parent entity.
- **Questions**: Linked to a quiz, contains content and media.
- **Options**: Linked to a question, used for choice-based types.
- **Attempts**: Represents a user's attempt at a quiz.
- **Answers**: Specific responses within an attempt, linked to a question.

## Extensibility
To add a new question type (e.g., "Sortable List"):
1. Create a new handler class in `App\Handlers\Questions\`.
2. Register the type in the `QuestionType` enum/config.
3. Add the corresponding frontend Blade component.
No changes are needed to the `QuizEvaluator` or `AttemptController`.

## Media Handling
- **Images**: Uploaded to `storage/app/public/media` and linked via `image_path`.
- **Videos**: Supports YouTube embed URLs which are parsed and rendered in an iframe.

## UI/UX Approach
- **Modern Aesthetics**: Using Inter font, CSS variables for theme consistency, and smooth transitions.
- **Responsive**: Mobile-first design for quiz attempts.
