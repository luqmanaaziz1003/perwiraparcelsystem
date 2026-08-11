<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['name']);  // from form input named 'name'
    $staffId = trim($_POST['staffId']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Basic validation
    if (empty($username) || empty($staffId) || empty($password) || empty($confirmPassword)) {
        echo "<script>alert('Please fill in all fields.'); window.history.back();</script>";
        exit;
    }

    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
        exit;
    }

    if (strlen($password) < 8 || strlen($password) > 12) {
        echo "<script>alert('Password must be between 8 and 12 characters.'); window.history.back();</script>";
        exit;
    }

    // Check if staffID already exists
    $stmt = $pdo->prepare('SELECT "staffID" FROM staff WHERE "staffID" = ?');
    $stmt->execute([$staffId]);

    if ($stmt->fetch()) {
        echo "<script>alert('Staff ID already registered. Please use a different Staff ID.'); window.history.back();</script>";
        exit;
    }

    // Hash the password before storing it. Never store the raw password.
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $insertStmt = $pdo->prepare('INSERT INTO staff (username, "staffID", password) VALUES (?, ?, ?)');

    try {
        $insertStmt->execute([$username, $staffId, $hashedPassword]);
        echo "<script>alert('Registration successful! Please login.'); window.location.href = 'staff-login.html';</script>";
        exit;
    } catch (PDOException $e) {
        // PDO throws on error, so a plain if/else on execute() would be dead code.
        if ($e->getCode() === '23000' || $e->getCode() === '23505') {
            echo "<script>alert('Staff ID already registered. Please use a different Staff ID.'); window.history.back();</script>";
        } else {
            error_log('Staff registration failed: ' . $e->getMessage());
            echo "<script>alert('Registration failed. Please try again later.'); window.history.back();</script>";
        }
        exit;
    }
} else {
    echo "<script>alert('Invalid request.'); window.history.back();</script>";
    exit;
}
