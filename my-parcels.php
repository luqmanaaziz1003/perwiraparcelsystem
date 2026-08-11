<?php
// Returns the logged-in receiver's own parcels as table rows.
session_start();
include 'db_connect.php';

if (!isset($_SESSION['icnumber'])) {
    http_response_code(403);
    echo "<tr><td colspan='5' class='text-center'>Please log in.</td></tr>";
    exit;
}

$stmt = $pdo->prepare(
    'SELECT "trackingNumber", platform, description, status, image_path
     FROM parcel WHERE "ICNo" = ? ORDER BY date_received DESC, time DESC'
);
$stmt->execute([$_SESSION['icnumber']]);
$parcels = $stmt->fetchAll();

if (!$parcels) {
    echo "<tr><td colspan='5' class='text-center text-muted'>You have not registered any parcels yet.</td></tr>";
    return;
}

foreach ($parcels as $p) {
    // Escape everything — these values came from user input.
    $tracking    = htmlspecialchars($p['trackingNumber'], ENT_QUOTES);
    $platform    = htmlspecialchars($p['platform'] ?? '', ENT_QUOTES);
    $description = htmlspecialchars($p['description'] ?? '', ENT_QUOTES);
    $status      = htmlspecialchars($p['status'] ?? '', ENT_QUOTES);

    if (!empty($p['image_path'])) {
        $img = htmlspecialchars($p['image_path'], ENT_QUOTES);
        $photo = "<a href='{$img}' target='_blank'><img src='{$img}' alt='parcel' style='width:48px;height:48px;object-fit:cover;border-radius:6px;'></a>";
    } else {
        $photo = "<span class='text-muted small'>—</span>";
    }

    $badge = $status === 'retrieved' ? 'success' : 'warning';

    echo "<tr>";
    echo "<td>{$photo}</td>";
    echo "<td>{$tracking}</td>";
    echo "<td>{$platform}</td>";
    echo "<td>{$description}</td>";
    echo "<td><span class='badge bg-{$badge}'>{$status}</span></td>";
    echo "</tr>";
}
