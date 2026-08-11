<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['staffID']) && isset($_POST['password'])) {
    $staffID = trim($_POST['staffID']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare('SELECT * FROM staff WHERE "staffID" = ?');
    $stmt->execute([$staffID]);
    $user = $stmt->fetch();

    if ($user) {
        if (password_matches($password, $user['password'])) {
            // Upgrade a legacy plaintext password to a hash on successful login.
            if (!password_get_info($user['password'])['algo']) {
                $upgrade = $pdo->prepare('UPDATE staff SET password = ? WHERE "staffID" = ?');
                $upgrade->execute([password_hash($password, PASSWORD_DEFAULT), $user['staffID']]);
            }
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
