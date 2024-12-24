<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "Script started.";

// Connect to MySQL Database
$servername = "localhost";
$username = "root";  // Default username for XAMPP
$password = "";      // Default password for XAMPP (blank)
$dbname = "tournament_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected to the database.<br>";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "Form was submitted.<br>";

    // Get form data
    $teamName = isset($_POST['teamName']) ? $_POST['teamName'] : '';
    $stadiumName = isset($_POST['stadiumName']) ? $_POST['stadiumName'] : '';

    // Simple validation
    if (!empty($teamName)) {
        // Check the current number of teams in the database
        $count_query = "SELECT COUNT(*) AS team_count FROM teams";
        $count_result = $conn->query($count_query);
        $row = $count_result->fetch_assoc();

        if ($row['team_count'] >= 4) {
            // Prevent additional registrations
            echo "<p style='color:red;'>You cannot register more than 4 teams!</p>";
        } else {
            // Use prepared statement to insert new team
            $stmt = $conn->prepare("INSERT INTO teams (team_name, stadium_name) VALUES (?, ?)");
            $stmt->bind_param("ss", $teamName, $stadiumName);

            if ($stmt->execute()) {
                echo "<p style='color:green;'>Team registered successfully!</p>";
            } else {
                echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
            }

            $stmt->close();
        }
    } else {
        echo "<p style='color:red;'>Team name is required!</p>";
    }
} else {
    echo "No form submitted.<br>";
}

$conn->close();
?>