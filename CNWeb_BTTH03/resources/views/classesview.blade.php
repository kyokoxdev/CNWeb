<?php
/** @var Classes $classes */
use App\Models\Classes;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .class-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .class-info {
            flex: 1;
        }
        .class-info h3 {
            margin: 0 0 10px 0;
            color: #2c3e50;
        }
        .class-info p {
            margin: 5px 0;
            color: #555;
        }
        .class-info strong {
            color: #333;
        }
        .view-button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .view-button:hover {
            background-color: #2980b9;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
            background: white;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Classes</h1>
        
        @if(is_iterable($classes) && (is_countable($classes) || is_array($classes)) && count($classes) > 0)
            @foreach($classes as $class)
                <div class="class-card">
                    <div class="class-info">
                        <h3>Class #{{ $class['id'] ?? $class->id }}</h3>
                        <p><strong>Grade Level:</strong> {{ $class['grade_level'] ?? $class->grade_level }}</p>
                        <p><strong>Room Number:</strong> {{ $class['room_number'] ?? $class->room_number }}</p>
                    </div>
                    <a href="{{ route('studentsview', ['class_id' => $class['id'] ?? $class->id]) }}" class="view-button">View Students</a>
                </div>
            @endforeach
        @else
            <div class="no-data">
                <p>No classes available.</p>
            </div>
        @endif
    </div>
</body>
</html>
