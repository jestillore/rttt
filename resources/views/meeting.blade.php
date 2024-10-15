<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meeting</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
            flex-direction: column;
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
            margin-bottom: 20px;
            padding: 25px;
        }

        .qr-container img {
            max-width: 100%;
            max-height: 100%;
        }

        .button {
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            font-family: 'Trebuchet MS';
        }

        .button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<div class="qr-container">
    {!! $joinQrCode !!}
</div>

<a class="button" href="{{ route('meetings.finish', $meeting->code) }}">End Meeting</a>
<script>
    // Web Speech API recognition setup
    let recognition = null;
    let isRecognizing = false;
    let sentences = [];

    // Initialize Web Speech API

    function initRecognition() {
        const SpeechRecognition =
            window.SpeechRecognition || window.webkitSpeechRecognition;
        if (!SpeechRecognition) {
            alert(
                "Your browser does not support the Web Speech API. Please try Google Chrome."
            );
            return;
        }

        recognition = new SpeechRecognition();
        recognition.continuous = true;
        recognition.interimResults = true;
        recognition.lang = '{{ $meeting->language }}'; // Set language based on selection
        recognition.maxAlternatives = 1;

        recognition.onstart = () => {
            isRecognizing = true;
        };

        recognition.onresult = (event) => {
            // Process the results and handle interim and final results
            for (let i = event.resultIndex; i < event.results.length; i++) {
                const transcript = event.results[i][0].transcript.trim();
                if (event.results[i].isFinal) {
                    processSentence(transcript);
                }
            }
        };

        recognition.onerror = (event) => {
            console.error("Recognition error:", event.error);
        };

        recognition.onend = () => {
            console.log('recognition end')
            isRecognizing = false;
        };
    }

    // Process sentences for sending to WebSocket
    async function processSentence(sentence) {
        fetch(
            `{{ route('meetings.sentences.store', $meeting->code) }}`,
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    sentence
                }),
            }
        );
    }
    initRecognition();
    recognition.start();
</script>
</body>
</html>
