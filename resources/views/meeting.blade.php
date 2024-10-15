<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Display</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
        }

        .qr-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 300px;
            height: 300px;
            background-color: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .qr-container img {
            max-width: 100%;
            max-height: 100%;
        }
    </style>
</head>
<body>

<div class="qr-container">
    {!! $joinQrCode !!}
</div>
</body>
</html>
