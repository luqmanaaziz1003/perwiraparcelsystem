<?php
// Uses the shared connection instead of its own hardcoded credentials.
include 'db_connect.php';

$stmt = $pdo->query('SELECT * FROM parcel');
$parcel = $stmt->fetchAll();

header('Content-Type: application/json');
echo json_encode($parcel);
