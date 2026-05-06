# Dynamic Quiz System - Laravel

A flexible, extensible Quiz System built with Laravel that supports multiple question types, media integration, and automated evaluation.

## Features
- **5 Question Types**: Binary, Single Choice, Multiple Choice, Number Input, Text Input.
- **Media Support**: Image uploads and YouTube video URLs for questions and options.
- **Extensible Logic**: Strategy-based evaluation engine.
- **Modern UI**: Responsive design with premium aesthetics.

## Requirements
- PHP 8.2+ (XAMPP recommended)
- Composer
- SQLite (default) or MySQL

## Setup Instructions

1. **Clone/Download the repository**
2. **Install Dependencies**
   ```bash
   composer install
   ```
3. **Environment Configuration**
   - Copy `.env.example` to `.env`
   - Set `DB_CONNECTION=sqlite`
   - Create an empty database file: `database/database.sqlite`
4. **Generate App Key**
   ```bash
   php artisan key:generate
   ```
5. **Run Migrations & Seeders**
   ```bash
   php artisan migrate --seed
   ```
6. **Link Storage**
   ```bash
   php artisan storage:link
   ```
7. **Run the Application**
   ```bash
   php artisan serve
   ```

## Admin Access
Currently, authentication is disabled as per requirements. You can access the Quiz Creator at:
`http://localhost:8000/admin/quizzes`

## Evaluation Logic
- **Binary/Single/Multiple Choice**: Exact match of selected options.
- **Number Input**: Numeric equality.
- **Text Input**: Case-insensitive string match.
- **Partial Credit**: Not implemented by default but supported by the architecture.
