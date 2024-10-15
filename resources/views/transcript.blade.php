<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presentation Transcripts</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .transcript {
            margin-bottom: 40px;
        }

        .transcript h2 {
            color: #555;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .transcript p {
            line-height: 1.6;
            color: #666;
        }

        footer {
            text-align: center;
            margin-top: 50px;
            font-size: 0.9rem;
            color: #999;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Transcripts</h1>
    @foreach($meeting->transcripts as $transcript)
        <div class="transcript">
            <p>{{ $transcript->content }}</p>
        </div>
    @endforeach
</div>

<footer>
    &copy; 2024 Real-Time Tongue Twisters. All rights reserved.
</footer>

</body>
</html>
