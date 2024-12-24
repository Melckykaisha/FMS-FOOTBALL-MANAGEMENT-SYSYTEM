<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Match Results</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Match Results</h1>
        <table>
            <thead>
                <tr>
                    <th>Round</th>
                    <th>Home Team</th>
                    <th>Away Team</th>
                    <th>Home Score</th>
                    <th>Away Score</th>
                </tr>
            </thead>
            <tbody id="matchResultsBody">
                <!-- Match results will be dynamically inserted here -->
            </tbody>
        </table>
    </div>

    <script>
        // Fetch match results from the backend
        fetch('get_match_results.php')
            .then(response => response.json())
            .then(data => {
                const resultsBody = document.getElementById('matchResultsBody');
                resultsBody.innerHTML = ''; // Clear any existing results

                // Loop through the match results and add them to the table
                data.forEach(match => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${match.round}</td>
                        <td>${match.home_team}</td>
                        <td>${match.away_team}</td>
                        <td>${match.home_score}</td>
                        <td>${match.away_score}</td>
                    `;
                    resultsBody.appendChild(row);
                });
            })
            .catch(error => console.error('Error fetching match results:', error));
    </script>
</body>
</html>
