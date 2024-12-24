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

// Retrieve scores data from the request
$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data)) {
    $success = true;

    foreach ($data as $score) {
        $match_id = $score['match_id'];
        $home_score = $score['home_score'];
        $away_score = $score['away_score'];

        // Check if the match_id already exists in the scores table
        $checkQuery = "SELECT * FROM scores WHERE match_id = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("i", $match_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // If the match_id exists, update the scores
            $updateQuery = "UPDATE scores SET home_score = ?, away_score = ? WHERE match_id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("iii", $home_score, $away_score, $match_id);
            if (!$updateStmt->execute()) {
                $success = false;
            }
            $updateStmt->close();
        } else {
            // If the match_id does not exist, insert new scores
            $insertQuery = "INSERT INTO scores (match_id, home_score, away_score) VALUES (?, ?, ?)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bind_param("iii", $match_id, $home_score, $away_score);
            if (!$insertStmt->execute()) {
                $success = false;
            }
            $insertStmt->close();
        }

        $stmt->close();
    }

    // Send response back to client
    if ($success) {
        echo json_encode(["success" => true, "message" => "Scores saved successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to save scores."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "No data received."]);
}

$conn->close();
?>
