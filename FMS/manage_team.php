<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tournament_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete team if 'delete' parameter is set
if (isset($_GET['delete'])) {
    $team_id = $_GET['delete'];
    $delete_sql = "DELETE FROM teams WHERE team_id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $team_id);
    $stmt->execute();
    $stmt->close();
    echo "<p style='color:green;'>Team deleted successfully.</p>";
}

// Fetch teams from database
$sql = "SELECT * FROM teams";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Teams</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            text-align: center;
        }
        .back-button {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #333;
            color: white;
        }
        .delete-button {
            color: white;
            background-color: orangered;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        .delete-button:hover {
            background-color: #cc3300;
        }
    </style>
</head>
<body>

<div class="container">
    <a href="admin_dashboard.php" class="back-button">Back to Admin Dashboard</a>
    <h1>Manage Teams</h1>
    <table>
        <tr>
            <th>Team ID</th>
            <th>Team Name</th>
            <th>Stadium</th>
            <th>Action</th>
        </tr>

        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row["team_id"] ?></td>
                    <td><?= htmlspecialchars($row["team_name"]) ?></td>
                    <td><?= htmlspecialchars($row["stadium_name"]) ?></td>
                    <td><a href="?delete=<?= $row["team_id"] ?>" class="delete-button">Delete</a></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No teams registered.</td>
            </tr>
        <?php endif; ?>
    </table>
</div>

</body>
</html>

<?php $conn->close(); ?>
