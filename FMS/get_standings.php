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

// Fetch scores and match details from the database
$sql = "SELECT m.home_team, m.away_team, s.home_score, s.away_score 
        FROM scores s
        JOIN matches m ON s.match_id = m.id";
$result = $conn->query($sql);

// Initialize an array to track team stats
$teamStats = [];

while ($row = $result->fetch_assoc()) {
    $homeTeam = $row['home_team'];
    $awayTeam = $row['away_team'];
    $homeScore = $row['home_score'];
    $awayScore = $row['away_score'];

    // Initialize stats for home team if not already set
    if (!isset($teamStats[$homeTeam])) {
        $teamStats[$homeTeam] = [
            'points' => 0,
            'goal_difference' => 0,
            'goals_scored' => 0,
            'goals_against' => 0
        ];
    }

    // Initialize stats for away team if not already set
    if (!isset($teamStats[$awayTeam])) {
        $teamStats[$awayTeam] = [
            'points' => 0,
            'goal_difference' => 0,
            'goals_scored' => 0,
            'goals_against' => 0
        ];
    }

    // Update goals scored and conceded
    $teamStats[$homeTeam]['goals_scored'] += $homeScore;
    $teamStats[$homeTeam]['goals_against'] += $awayScore;
    $teamStats[$awayTeam]['goals_scored'] += $awayScore;
    $teamStats[$awayTeam]['goals_against'] += $homeScore;

    // Update points based on match result
    if ($homeScore > $awayScore) {
        // Home team wins
        $teamStats[$homeTeam]['points'] += 3;
    } elseif ($homeScore < $awayScore) {
        // Away team wins
        $teamStats[$awayTeam]['points'] += 3;
    } else {
        // Draw
        $teamStats[$homeTeam]['points'] += 1;
        $teamStats[$awayTeam]['points'] += 1;
    }

    // Update goal difference
    $teamStats[$homeTeam]['goal_difference'] = $teamStats[$homeTeam]['goals_scored'] - $teamStats[$homeTeam]['goals_against'];
    $teamStats[$awayTeam]['goal_difference'] = $teamStats[$awayTeam]['goals_scored'] - $teamStats[$awayTeam]['goals_against'];
}

// Sort the teams based on points, goal difference, and goals scored
usort($teamStats, function($a, $b) {
    if ($a['points'] === $b['points']) {
        if ($a['goal_difference'] === $b['goal_difference']) {
            return $b['goals_scored'] - $a['goals_scored']; // Sort by goals scored
        }
        return $b['goal_difference'] - $a['goal_difference']; // Sort by goal difference
    }
    return $b['points'] - $a['points']; // Sort by points
});

// Prepare the response
$standings = [];
foreach ($teamStats as $teamName => $stats) {
    $standings[] = [
        'team' => $teamName,
        'points' => $stats['points'],
        'goal_difference' => $stats['goal_difference'],
        'goals_scored' => $stats['goals_scored'],
        'goals_against' => $stats['goals_against']
    ];
}

// Return standings as JSON
echo json_encode($standings);

// Close the database connection
$conn->close();
?>
