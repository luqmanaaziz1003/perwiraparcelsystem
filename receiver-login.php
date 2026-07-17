<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $icnumber = trim($_POST['icnumber']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM Receiver WHERE ICNo = ?");
    $stmt->bind_param("s", $icnumber);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // ONLY redirect if password matches
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
?>
