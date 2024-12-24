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

// Fetch match results
$sql = "SELECT m.round, m.home_team, m.away_team, s.home_score, s.away_score 
        FROM matches m
        JOIN scores s ON m.id = s.match_id
        ORDER BY m.round ASC";
$result = $conn->query($sql);

// Initialize an array to store match results
$matches = [];

while ($row = $result->fetch_assoc()) {
    $matches[] = [
        'round' => $row['round'],
        'home_team' => $row['home_team'],
        'away_team' => $row['away_team'],
        'home_score' => $row['home_score'],
        'away_score' => $row['away_score']
    ];
}

// Return the match results as JSON
echo json_encode($matches);

// Close the database connection
$conn->close();
?>
