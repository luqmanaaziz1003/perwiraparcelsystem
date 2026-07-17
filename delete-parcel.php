<?php
// delete.php
include 'db_connect.php'; // adjust to your connection file

if (isset($_POST['trackingNumber'])) {
    $trackingNumber = $_POST['trackingNumber'];

    $sql = "DELETE FROM parcel WHERE trackingNumber = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $trackingNumber);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "error" => "No tracking number provided"]);
}
?>
