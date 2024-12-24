<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tournament_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Fetch teams from the database
$sql = "SELECT team_name FROM teams";
$result = $conn->query($sql);

if ($result->num_rows < 4) {
    echo json_encode(["error" => "At least 4 teams are required to generate fixtures."]);
    exit;
}

$teams = [];
while ($row = $result->fetch_assoc()) {
    $teams[] = $row['team_name'];
}

// Ensure exactly 4 teams are used
$teams = array_slice($teams, 0, 4);

// Generate fixtures (round-robin for 4 teams)
$fixtures = [];
$totalRounds = count($teams) - 1; // 3 rounds for 4 teams
$numTeams = count($teams);

// Rotate teams for round-robin scheduling
for ($round = 1; $round <= $totalRounds; $round++) {
    $matches = [];
    for ($i = 0; $i < $numTeams / 2; $i++) {
        $home = $teams[$i];
        $away = $teams[$numTeams - 1 - $i];

        $matches[] = [
            "home" => $home,
            "away" => $away
        ];

        // Insert match into the database
        $stmt = $conn->prepare("INSERT INTO matches (round, home_team, away_team) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $round, $home, $away);
        $stmt->execute();
    }

    // Rotate the array of teams (except the first team)
    $lastTeam = array_pop($teams);
    array_splice($teams, 1, 0, $lastTeam);

    $fixtures[] = ["round" => $round, "matches" => $matches];
}

// Return fixtures as JSON
echo json_encode($fixtures);
$conn->close();
?>
