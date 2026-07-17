<?php
    // Include your DB connection file (if not yet included)
    include 'db_connect.php'; // or whatever your DB connection file is named

    // Fetch all parcels
    $sql = "SELECT * FROM parcel ORDER BY date_received DESC";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($parcel = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>{$parcel['trackingNumber']}</td>";
            echo "<td>{$parcel['status']}</td>";
            echo "<td>
<button 
  class='btn btn-warning btn-sm' 
  data-tracking='{$parcel['trackingNumber']}'
  data-icno='{$parcel['ICNo']}'
  data-weight='{$parcel['weight']}'
  data-size='{$parcel['size']}'
  data-location='{$parcel['deliveryLocation']}'
  onclick='showEditModal(this)'
>Update</button>                    <button class='btn btn-info btn-sm'
                        data-tracking='{$parcel['trackingNumber']}'
                        data-weight='{$parcel['weight']}'
                        data-size='{$parcel['size']}'
                        data-location='{$parcel['deliveryLocation']}'
                        onclick='showParcelDetails(this)'>
                        Details
                    </button>
                    <button class='btn btn-danger btn-sm' onclick=\"deleteParcel('{$parcel['trackingNumber']}')\">Delete</button>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='3' class='text-center'>No parcels found.</td></tr>";
    }
  ?>