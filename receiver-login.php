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
        if (password_matches($password, $user['password'])) {
            // If this account still has a legacy plaintext password, upgrade it
            // to a hash now that we know the real password.
            if (!password_get_info($user['password'])['algo']) {
                $upgrade = $pdo->prepare('UPDATE receiver SET password = ? WHERE "ICNo" = ?');
                $upgrade->execute([password_hash($password, PASSWORD_DEFAULT), $user['ICNo']]);
            }
            $_SESSION['icnumber'] = $user['ICNo'];
            $_SESSION['username'] = $user['username'];
            echo "<script>alert('Login successful!'); window.location.href = 'receiver-dashboard.php';</script>";
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
