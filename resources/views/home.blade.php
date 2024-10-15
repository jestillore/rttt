<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create a Meeting</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-bottom: 10px;
            color: #555;
        }
        select, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        textarea {
            resize: vertical;
            height: 100px;
            width: 94.5%;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Create a Meeting</h1>
    <form action="{{ route('meetings.store') }}" method="POST">
        @csrf
        <label for="language">Choose a Language:</label>
        <select id="language" name="language">
            <option value="en-uS">English</option>
            <option value="ar">Arabic</option>
            <option value="es">Spanish</option>
            <option value="fr">French</option>
            <option value="zh">Chinese</option>
            <!-- Add more languages as needed -->
        </select>

        <label for="meeting-details">Meeting Details / Notes:</label>
        <textarea name="context" id="meeting-details" placeholder="Enter any additional details or notes about the meeting..."></textarea>

        <button type="submit">Create a Meeting</button>
    </form>
</div>

</body>
</html>
