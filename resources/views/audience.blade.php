<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meeting Details</title>
    <style>
        /* Basic reset and utility classes */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
        }
        .card {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            width: 80%;
            max-width: 500px;
        }
    </style>
      <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
      // Enable pusher logging - don't include this in production
      Pusher.logToConsole = true;

      const meetingId = '{{ $meeting->code }}';
      const audienceId = {{ $audience->id }};
      const pusher_api_key = "{{ config('broadcasting.connections.pusher.key') }}"

      var pusher = new Pusher(pusher_api_key, {
        cluster: 'eu'
      });

      var channel = pusher.subscribe(meetingId);
      channel.bind(`audience.${audienceId}`, function(event) {
        console.log(event);
        queueSpeech(event.message);
      });

      channel.bind(`audience.${audienceId}.done`, function(data) {
        redirectToSummary(data);
      });

      // Queue for sentences to be spoken
      let speechQueue = [];

      // Function to add a sentence to the speech queue
      function queueSpeech(text) {
        speechQueue.push(text);
        // Start speaking if no other speech is in progress
        if (!speechSynthesis.speaking) {
            playNextInQueue();
        }
      }

      // Function to play the next sentence in the queue
      function playNextInQueue() {
        if (speechQueue.length === 0) return; // No sentences in queue

        const sentence = speechQueue.shift(); // Get the next sentence
        const utterance = new SpeechSynthesisUtterance(sentence);

        utterance.onend = function() {
            // When current speech finishes, play the next one
            playNextInQueue();
        };

        utterance.onerror = function() {
            console.error('Speech synthesis error.');
            // Move on to the next sentence in case of an error
            playNextInQueue();
        };

        // Start speaking the sentence
        speechSynthesis.speak(utterance);
      }

      function redirectToSummary(data) {
        // Construct the URL dynamically using the Blade variables
        const url = `/meetings/${meetingId}/audiences/${audienceId}/summary`;

        // Redirect to the constructed URL
        window.location.href = url;
    }
    </script>
</head>
<body>
    <div class="card">
        <h1>Meeting Details</h1>
        <p>Audience ID: {{ $audience->id }}</p>
        <p>Audience Language: {{ $audience->language }} </p>
    </div>
</body>
</html>
