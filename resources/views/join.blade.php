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
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 16px;
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
    <h1>Join Meeting</h1>
    <form action="{{ route('meetings.audiences.store', $meeting->code) }}" method="POST">
        @csrf
        <label for="language">Choose a Language:</label>
        <select id="language" name="language">
            <option value="English">English</option>
            <option value="Swedish">Swedish</option>
            <option value="Spanish">Spanish</option>
            <option value="Urdu">Urdu</option>
            <option value="Hindi">Hindi</option>
            <option value="Punjabi">Punjabi</option>
            <option value="Indonesian">Indonesian</option>
            <option value="Cebuano">Cebuano</option>
            <option value="German">German</option>
            <option value="Ukrainian">Ukrainian</option>
            <option value="Telugu">Telugu</option>
        </select>
        <button type="submit">Join Meeting</button>
    </form>
</div>

</body>
</html>
