<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['name']);
    $icnumber = trim($_POST['icnumber']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // 1. Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
        exit();
    }

    // 2. Check for existing IC
    $check = $conn->prepare("SELECT ICNo FROM Receiver WHERE ICNo = ?");
    if (!$check) {
        echo "<script>alert('Database error: " . $conn->error . "'); window.history.back();</script>";
        exit();
    }
    $check->bind_param("s", $icnumber);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('IC number is already registered.'); window.history.back();</script>";
        $check->close();
        exit();
    }
    $check->close();

    // 3. Insert user WITHOUT hashing password
    $stmt = $conn->prepare("INSERT INTO Receiver (ICNo, username, phone_number, password) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        echo "<script>alert('Database error: " . $conn->error . "'); window.history.back();</script>";
        exit();
    }
    $stmt->bind_param("ssss", $icnumber, $username, $phone, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful!'); window.location.href='receiver-login.html';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Invalid request.'); window.history.back();</script>";
}
?>
