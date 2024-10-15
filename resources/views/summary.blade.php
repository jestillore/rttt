<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Call Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1, h2 {
            color: #333;
        }
        .summary-section {
            margin-bottom: 20px;
        }
        .summary-section p {
            font-size: 18px;
            line-height: 1.6;
        }
        .summary-section p span {
            font-weight: bold;
        }
        .summary-section hr {
            border: none;
            border-bottom: 1px solid #ccc;
            margin: 10px 0;
        }
    </style>
</head>
<body>

<div class="container">
    @if($audience->summary)
        <div class="summary-section">
            <h2>Summary</h2>
            <p>{{ $audience->summary }}</p>
        </div>
    @else
        <div class="summary-section">
            <p>Preparing summary ...</p>
        </div>
    @endif
</div>

</body>
</html>
