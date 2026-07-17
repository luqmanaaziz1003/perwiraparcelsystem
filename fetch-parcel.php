<?php
    include 'db_connect.php';

    // Fetch all parcels
    $stmt = $pdo->query('SELECT * FROM parcel ORDER BY date_received DESC');
    $parcels = $stmt->fetchAll();

    if ($parcels) {
        foreach ($parcels as $parcel) {
            // htmlspecialchars: these values reach the page as HTML attributes,
            // and a tracking number or location containing a quote would
            // otherwise break out of the attribute.
            $tracking = htmlspecialchars($parcel['trackingNumber'], ENT_QUOTES);
            $status   = htmlspecialchars($parcel['status'], ENT_QUOTES);
            $icno     = htmlspecialchars($parcel['ICNo'], ENT_QUOTES);
            $weight   = htmlspecialchars($parcel['weight'], ENT_QUOTES);
            $size     = htmlspecialchars($parcel['size'], ENT_QUOTES);
            $location = htmlspecialchars($parcel['deliveryLocation'], ENT_QUOTES);

            echo "<tr>";
            echo "<td>{$tracking}</td>";
            echo "<td>{$status}</td>";
            echo "<td>
<button
  class='btn btn-warning btn-sm'
  data-tracking='{$tracking}'
  data-icno='{$icno}'
  data-weight='{$weight}'
  data-size='{$size}'
  data-location='{$location}'
  onclick='showEditModal(this)'
>Update</button>                    <button class='btn btn-info btn-sm'
                        data-tracking='{$tracking}'
                        data-weight='{$weight}'
                        data-size='{$size}'
                        data-location='{$location}'
                        onclick='showParcelDetails(this)'>
                        Details
                    </button>
                    <button class='btn btn-danger btn-sm' onclick=\"deleteParcel('{$tracking}')\">Delete</button>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='3' class='text-center'>No parcels found.</td></tr>";
    }
