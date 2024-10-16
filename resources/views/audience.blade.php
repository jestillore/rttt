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
        #audioPlayer {
            visibility: hidden;
        }
    </style>
      <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    
</head>
<body>
    <div class="card">
        <h1>Meeting Details</h1>
        <p>Audience ID: {{ $audience->id }}</p>
        <p>Audience Language: {{ $audience->language }} </p>
        <!-- Audio element -->
        <audio id="audioPlayer" controls style=""></audio>
    </div>
</body>
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
        queueAudio(event.url);
      });

      channel.bind(`audience.${audienceId}.done`, function(data) {
        redirectToSummary(data);
      });

      let audioQueue = [];

      // Function to queue audio tracks
      function queueAudio(url) {
        audioQueue.push(url);  // Add the audio URL to the queue

        // If no audio is currently playing, start playing the next one in the queue
        if (audioPlayer().paused && audioQueue.length === 1) {
            playNextInQueue();
        }
      }

      function audioPlayer() {
        return document.getElementById('audioPlayer');
      }

        // Function to play the next audio in the queue
        function playNextInQueue() {
            if (audioQueue.length > 0) {
                const nextAudio = audioQueue[0];  // Get the first item in the queue
                audioPlayer().src = nextAudio;
                audioPlayer().play();
            }
        }

        // Event listener to detect when the current audio has finished playing
        audioPlayer().addEventListener('ended', function() {
            audioQueue.shift();  // Remove the played audio from the queue
            playNextInQueue();   // Play the next audio in the queue
        });

      function redirectToSummary(data) {
        // Construct the URL dynamically using the Blade variables
        const url = `/meetings/${meetingId}/audiences/${audienceId}/summary`;

        // Redirect to the constructed URL
        window.location.href = url;
    }
    </script>
</html>
