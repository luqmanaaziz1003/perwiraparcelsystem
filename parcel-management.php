<?php
// Database connection
$servername = "localhost";
$username = "root"; // Change if needed
$password = "";     // Change if you set one in XAMPP
$dbname = "project_db"; // Replace with your actual DB name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch parcel from the database
$sql = "SELECT * FROM parcel"; // Change 'parcel' if your table name is different
$result = $conn->query($sql);

$parcel = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $parcel[] = $row;
    }
}

// Return JSON
header('Content-Type: application/json');
echo json_encode($parcel);

// Close connection
$conn->close();
?>
