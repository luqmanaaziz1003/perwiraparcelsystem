<?php
// Receiver registers a parcel they are expecting. The IC comes from the
// logged-in session, never from the form, so a receiver can only add parcels
// under their own account.
session_start();
include 'db_connect.php';

if (!isset($_SESSION['icnumber'])) {
    header('Location: receiver-login.html');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: receiver-dashboard.php');
    exit;
}

$ICNo           = $_SESSION['icnumber'];
$name           = $_SESSION['username'] ?? '';
$trackingNumber = trim($_POST['trackingNumber'] ?? '');
$platform       = trim($_POST['platform'] ?? '');
$description    = trim($_POST['description'] ?? '');

// Small helper so every failure returns the receiver to the dashboard with a message.
function fail(string $msg): void {
    echo "<script>alert(" . json_encode($msg) . "); window.location.href='receiver-dashboard.php';</script>";
    exit;
}

if ($trackingNumber === '' || $platform === '' || $description === '') {
    fail('Please fill in the tracking number, platform, and description.');
}

// Tracking number is the primary key — reject duplicates before inserting.
$check = $pdo->prepare('SELECT 1 FROM parcel WHERE "trackingNumber" = ?');
$check->execute([$trackingNumber]);
if ($check->fetch()) {
    fail('That tracking number is already registered.');
}

// ---- Optional photo upload -------------------------------------------------
$imagePath = null;
if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
    $photo = $_FILES['photo'];

    if ($photo['error'] !== UPLOAD_ERR_OK) {
        fail('The photo failed to upload. Please try again.');
    }
    if ($photo['size'] > 5 * 1024 * 1024) {
        fail('The photo must be 5 MB or smaller.');
    }

    // Trust the file's actual content, not its name or the browser-sent type.
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($photo['tmp_name']);
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
    ];
    if (!isset($allowed[$mime])) {
        fail('Only JPG, PNG, WEBP, or GIF images are allowed.');
    }

    // Generate our own filename so a malicious name can't traverse the path or
    // overwrite another parcel's photo.
    $safeTracking = preg_replace('/[^A-Za-z0-9_-]/', '', $trackingNumber);
    $filename = $safeTracking . '_' . bin2hex(random_bytes(4)) . '.' . $allowed[$mime];
    $destDir  = __DIR__ . '/uploads/parcels';
    if (!move_uploaded_file($photo['tmp_name'], $destDir . '/' . $filename)) {
        fail('Could not save the photo. Please try again.');
    }
    // Store a web-relative path so pages can display it directly.
    $imagePath = 'uploads/parcels/' . $filename;
}

// ---- Insert ----------------------------------------------------------------
$date = date('Y-m-d');
$time = date('H:i:s');

try {
    $stmt = $pdo->prepare(
        'INSERT INTO parcel ("trackingNumber", "ICNo", date_received, time, name, status, platform, description, image_path)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([$trackingNumber, $ICNo, $date, $time, $name, 'pending', $platform, $description, $imagePath]);
    echo "<script>alert('Parcel registered! We will notify you when it arrives.'); window.location.href='receiver-dashboard.php';</script>";
} catch (PDOException $e) {
    // If the insert failed after the photo was saved, don't leave it orphaned.
    if ($imagePath !== null && file_exists(__DIR__ . '/' . $imagePath)) {
        unlink(__DIR__ . '/' . $imagePath);
    }
    error_log('Receiver add parcel failed: ' . $e->getMessage());
    fail('Could not register the parcel. Please try again.');
}
