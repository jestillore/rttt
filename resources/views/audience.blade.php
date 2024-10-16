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
            width: 100%;
            max-width: 600px;
            position: relative;
        }
        #audioPlayer {
            visibility: hidden;
        }
        #caption {
          font-size: 24px;
          font-family: Arial, sans-serif;
          white-space: pre-wrap;
          word-wrap: break-word;
          margin-top: 20px;
          position: fixed;
        }
      </style>
      <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
</head>
<body>
    <div>
      <div class="card">
          <h1>Meeting Details</h1>
          <p>Audience ID: {{ $audience->id }}</p>
          <p>Audience Language: {{ $audience->language }} </p>
          <!-- Audio element -->
          <audio id="audioPlayer" controls></audio>
      </div>
      <div id="caption"></div>
    </div>
</body>
<script>
      // Enable pusher logging - don't include this in production
      Pusher.logToConsole = true;

      const meetingId = '{{ $meeting->code }}';
      const audienceId = {{ $audience->id }};
      const pusher_api_key = "{{ config('broadcasting.connections.pusher.key') }}"
      const captionElement = document.getElementById("caption");

      var pusher = new Pusher(pusher_api_key, {
        cluster: 'eu'
      });

      var channel = pusher.subscribe(meetingId);
      channel.bind(`audience.${audienceId}`, function(event) {
        console.log(event);
        if (event.audioUrl) {
          queueAudio(event);
        }
      });

      channel.bind(`audience.${audienceId}.done`, function(data) {
        redirectToSummary(data);
      });

      let audioQueue = [];

      // Function to queue audio tracks
      function queueAudio(event) {
        audioQueue.push(event);  // Add the audio URL to the queue

        // If no audio is currently playing, start playing the next one in the queue
        if (audioPlayer().paused && audioQueue.length > 0) {
            playNextInQueue();
        }
      }

      function audioPlayer() {
        return document.getElementById('audioPlayer');
      }

      // Function to play the next audio in the queue
      function playNextInQueue() {
        const nextData = audioQueue[0];
          if (audioQueue.length > 0) {
            const nextAudio = nextData.audioUrl;  // Get the first item in the queue
            audioPlayer().src = nextAudio;
            audioPlayer().load();
            audioPlayer().play();

            displayCaptions([nextData.translatedMessage], captionElement);
          }
      }

      // Event listener to detect when the current audio has finished playing
      audioPlayer().addEventListener('ended', function() {
          audioQueue.shift();  // Remove the played audio from the queue
          playNextInQueue();   // gPlay the next audio in the queue
      });

      function redirectToSummary(data) {
        // Construct the URL dynamically using the Blade variables
        const url = `/meetings/${meetingId}/audiences/${audienceId}/summary`;

        // Redirect to the constructed URL
        window.location.href = url;
      }

      const typewriterEffect = (text, element, speed = 100) => {
        return new Promise((resolve) => {
          let i = 0;
          const interval = setInterval(() => {
            element.textContent += text[i];
            i++;
            if (i === text.length) {
              clearInterval(interval);
              resolve(); // Proceed to the next caption
            }
          }, speed);
        });
      };

      const displayCaptions = async (captions, element) => {
        for (let caption of captions) {
          element.textContent = ""; // Clear previous caption
          await typewriterEffect(caption, element); // Wait until the caption is fully displayed
          await new Promise((resolve) => setTimeout(resolve, 100)); // Pause before next caption
        }
      };
    </script>
</html>
