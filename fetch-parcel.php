<?php
    include 'db_connect.php';

    // All parcels, newest first, for the staff management table.
    $stmt = $pdo->query('SELECT * FROM parcel ORDER BY date_received DESC, time DESC');
    $parcels = $stmt->fetchAll();

    if ($parcels) {
        foreach ($parcels as $parcel) {
            // Escape everything — these values reach the page as HTML attributes.
            $tracking    = htmlspecialchars($parcel['trackingNumber'], ENT_QUOTES);
            $status      = htmlspecialchars($parcel['status'], ENT_QUOTES);
            $icno        = htmlspecialchars($parcel['ICNo'] ?? '', ENT_QUOTES);
            $name        = htmlspecialchars($parcel['name'] ?? '', ENT_QUOTES);
            $platform    = htmlspecialchars($parcel['platform'] ?? '', ENT_QUOTES);
            $description = htmlspecialchars($parcel['description'] ?? '', ENT_QUOTES);
            $image       = htmlspecialchars($parcel['image_path'] ?? '', ENT_QUOTES);

            $badge = $status === 'retrieved' ? 'success' : 'warning';

            // "Mark retrieved" only makes sense while the parcel is still pending.
            $retrieveBtn = $status === 'pending'
                ? "<button class='btn btn-success btn-sm' onclick=\"markRetrieved('{$tracking}')\">Mark Retrieved</button>"
                : "";

            echo "<tr>";
            echo "<td>{$tracking}</td>";
            echo "<td><span class='badge bg-{$badge}'>{$status}</span></td>";
            echo "<td>
                    <button class='btn btn-info btn-sm'
                        data-tracking='{$tracking}'
                        data-icno='{$icno}'
                        data-name='{$name}'
                        data-platform='{$platform}'
                        data-description='{$description}'
                        data-image='{$image}'
                        onclick='showParcelDetails(this)'>Details</button>
                    {$retrieveBtn}
                    <button class='btn btn-danger btn-sm' onclick=\"deleteParcel('{$tracking}')\">Delete</button>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='3' class='text-center'>No parcels found.</td></tr>";
    }
