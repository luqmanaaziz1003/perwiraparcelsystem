<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['staffID']) && isset($_POST['password'])) {
    $staffID = trim($_POST['staffID']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM Staff WHERE staffID = ?");
    $stmt->bind_param("s", $staffID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // ONLY redirect if password matches
        if ($password === $user['password']) {
            $_SESSION['staffID'] = $user['staffID'];
            $_SESSION['username'] = $user['username'];
            echo "<script>alert('Login successful!'); window.location.href = 'staff-dashboard.php';</script>";
            exit;
        } else {
            echo "<script>alert('Incorrect password.'); window.history.back();</script>";
            exit;
        }
    } else {
        echo "<script>alert('ID not found.'); window.history.back();</script>";
        exit;
    }
}
else {
    echo "<script>alert('Invalid request.'); window.history.back();</script>";
    exit;
}
?>
