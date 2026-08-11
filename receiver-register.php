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
    $check = $pdo->prepare('SELECT "ICNo" FROM receiver WHERE "ICNo" = ?');
    $check->execute([$icnumber]);

    if ($check->fetch()) {
        echo "<script>alert('IC number is already registered.'); window.history.back();</script>";
        exit();
    }

    // 3. Check for existing phone number — it has a UNIQUE index, so without
    //    this the INSERT below throws instead of telling the user why.
    $checkPhone = $pdo->prepare('SELECT "ICNo" FROM receiver WHERE phone_number = ?');
    $checkPhone->execute([$phone]);

    if ($checkPhone->fetch()) {
        echo "<script>alert('This phone number is already registered.'); window.history.back();</script>";
        exit();
    }

    // 4. Hash the password before storing it. Never store the raw password.
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare('INSERT INTO receiver ("ICNo", username, phone_number, password) VALUES (?, ?, ?, ?)');

    try {
        $stmt->execute([$icnumber, $username, $phone, $hashedPassword]);
        echo "<script>alert('Registration successful!'); window.location.href='receiver-login.html';</script>";
    } catch (PDOException $e) {
        // 23000 (MySQL) / 23505 (Postgres) = integrity constraint violation.
        // Another request can win the race between the checks above and this insert.
        if ($e->getCode() === '23000' || $e->getCode() === '23505') {
            echo "<script>alert('That IC number or phone number is already registered.'); window.history.back();</script>";
        } else {
            error_log('Receiver registration failed: ' . $e->getMessage());
            echo "<script>alert('Registration failed. Please try again later.'); window.history.back();</script>";
        }
    }
}
else {
    echo "<script>alert('Invalid request.'); window.history.back();</script>";
}
