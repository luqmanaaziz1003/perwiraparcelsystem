<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $icnumber = trim($_POST['icnumber']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare('SELECT * FROM receiver WHERE "ICNo" = ?');
    $stmt->execute([$icnumber]);
    $user = $stmt->fetch();

    if ($user) {
        // ONLY redirect if password matches
        // NOTE: still a plaintext comparison — see password_verify() below.
        if ($password === $user['password']) {
            $_SESSION['icnumber'] = $user['ICNo'];
            $_SESSION['username'] = $user['username'];
            echo "<script>alert('Login successful!'); window.location.href = 'receiver-dashboard.html';</script>";
            exit;
        } else {
            echo "<script>alert('Incorrect password.'); window.history.back();</script>";
            exit;
        }
    } else {
        echo "<script>alert('IC number not found.'); window.history.back();</script>";
        exit;
    }
}
else {
    echo "<script>alert('Invalid request.'); window.history.back();</script>";
    exit;
}
