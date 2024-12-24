<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Match Scores</title>
    <style>
        body {
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        .match {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .scores input {
            width: 50px;
            text-align: center;
        }
        button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Input Match Scores</h1>
        <div id="matchContainer">Loading matches...</div>
        <button id="saveScoresButton">Save Scores</button>
    </div>

    <script>
        // Fetch fixtures from the PHP file
        fetch('generate_fixtures.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                const matchContainer = document.getElementById('matchContainer');
                matchContainer.innerHTML = ''; // Clear loading text

                // Display each round and its matches
                data.forEach(round => {
                    const roundDiv = document.createElement('div');
                    roundDiv.innerHTML = `<h2>Round ${round.round}</h2>`;

                    round.matches.forEach(match => {
                        const matchDiv = document.createElement('div');
                        matchDiv.classList.add('match');

                        matchDiv.innerHTML = `
                            <span>${match.home} vs ${match.away}</span>
                            <div class="scores">
                                <input type="number" id="home_${match.match_id}" placeholder="Home" />
                                <input type="number" id="away_${match.match_id}" placeholder="Away" />
                            </div>
                        `;

                        roundDiv.appendChild(matchDiv);
                    });

                    matchContainer.appendChild(roundDiv);
                });
            })
            .catch(error => {
                console.error('Error loading fixtures:', error);
                document.getElementById('matchContainer').innerHTML = '<p style="color: red;">Failed to load fixtures.</p>';
            });

        // Save scores to the database
        document.getElementById('saveScoresButton').addEventListener('click', () => {
            const scores = [];
            const inputs = document.querySelectorAll('.scores input');

            inputs.forEach(input => {
                const [type, matchId] = input.id.split('_');
                const scoreIndex = scores.findIndex(score => score.match_id === matchId);

                if (scoreIndex === -1) {
                    scores.push({
                        match_id: matchId,
                        home_score: type === 'home' ? parseInt(input.value) || 0 : null,
                        away_score: type === 'away' ? parseInt(input.value) || 0 : null
                    });
                } else {
                    scores[scoreIndex][`${type}_score`] = parseInt(input.value) || 0;
                }
            });

            console.log("Scores to be saved:", scores); // Log scores to the console for debugging

            // Send scores to the backend
            fetch('save_scores.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(scores),
            })
            .then(response => response.json())
            .then(responseData => {
                if (responseData.success) {
                    alert(responseData.message);
                } else {
                    alert(responseData.message);
                }
            })
            .catch(error => console.error('Error saving scores:', error));
        });
    </script>
</body>
</html>
