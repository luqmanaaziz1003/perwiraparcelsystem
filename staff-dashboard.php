<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPC Staff Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="assets/Icon Web.ico" type="image/x-icon">
    <link rel="stylesheet" href="style.css">

</head>
<body class="staff-dashboard-body">
<!-- JS Library (QRCode.js) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg staff-navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="#" style="color: #fff; font-family: 'Poppins', sans-serif;">
                PERWIRA PARCEL CENTER
                <img src="assets/Icon Web.ico" alt="Parcel Icon" class="me-2" style="width: 30px; height: 30px;">
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <button class="btn btn-link nav-link" onclick="redirectToLandingPage()" style="
                            background: linear-gradient(135deg, #667eea, #764ba2);
                            border: none;
                            border-radius: 25px;
                            padding: 8px 20px;
                            color: white;
                            font-weight: 500;
                            transition: all 0.3s ease;
                            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Dashboard Container -->
    <div class="container mt-4">
        <h2 class="mb-4" style="font-weight: 600;">Welcome !</h2>

<!-- Details Modal (Input Form) -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="detailsModalLabel">Parcel Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
<form action="add-parcel.php" method="POST">
  <div class="modal-body">

    <div class="mb-3">
    <label for="ICNo" class="form-label">Receiver IC Number</label>
    <input type="text" class="form-control" name="ICNo" id="ICNo" required>
    </div>

    <div class="mb-3">
      <label for="trackingNumber" class="form-label">Tracking Number</label>
      <input type="text" class="form-control" name="trackingNumber" id="trackingNumber" required>
    </div>
    <div class="mb-3">
      <label for="weight" class="form-label">Weight (kg)</label>
      <input type="number" step="0.01" class="form-control" name="weight" id="weight" required>
    </div>
    <div class="mb-3">
      <label for="size" class="form-label">Size</label>
      <select class="form-select" name="size" id="size" required>
        <option selected disabled>Select size</option>
        <option value="S">Small</option>
        <option value="M">Medium</option>
        <option value="L">Large</option>
      </select>
    </div>
    <div class="mb-3">
      <label for="deliveryLocation" class="form-label">Delivery Location</label>
      <input type="text" class="form-control" name="deliveryLocation" id="deliveryLocation" required>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary">Add Parcel</button>
  </div>
</form>
      </div>
    </div>
  </div>
  
  <!-- PC management DETAILS POP UP -->
<div class="modal fade" id="outputDetailsModal" tabindex="-1" aria-labelledby="outputDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="outputDetailsModalLabel">Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Output Form Fields -->
                <div class="mb-3">
                    <label for="outputTrackingNumber" class="form-label">Tracking Number:</label>
                    <input type="text" class="form-control" id="outputTrackingNumber" value="PPC9876543210" readonly disabled>
                </div>
                <div class="mb-3">
                    <label for="outputParcelWeight" class="form-label">Parcel Weight:</label>
                    <div class="d-flex">
                        <input type="number" class="form-control" id="outputParcelWeight" value="1.5" readonly disabled style="flex: 1; margin-right: 10px;" />
<div class="d-inline-block p-1" style="width: auto;">
  <input type="text" class="form-control" value="kg" readonly style="width: 70px;">
</div>


                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Parcel Size:</label>
                    <div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="outputParcelSize" id="outputSizeLight" value="light" checked readonly disabled />
                            <label class="form-check-label" for="outputSizeLight">Small</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="outputParcelSize" id="outputSizeMedium" value="medium" readonly disabled />
                            <label class="form-check-label" for="outputSizeMedium">Medium</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="outputParcelSize" id="outputSizeHeavy" value="heavy" readonly disabled />
                            <label class="form-check-label" for="outputSizeHeavy">Large</label>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="outputDeliveryLocation" class="form-label">Delivery Location:</label>
                    <input type="text" class="form-control" id="outputDeliveryLocation" value="123 Main St, Anytown, USA" readonly disabled>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

        <ul class="nav nav-tabs" id="receiverDashboardTabs" role="tablist">
            <li class="nav-item">
                <button class="tab active" id="management-tab" data-bs-toggle="tab" data-bs-target="#tracking" type="button" role="tab">Parcel Management</button>
            </li>
            <li class="nav-item">
                <button class="tab" id="add-parcel-tab" data-bs-toggle="tab" data-bs-target="#addparcel" type="button" role="tab">New Parcel</button>
            </li>
        </ul>
        
        <div class="tab-content">
            <!-- Parcel Management Tab -->
            <div class="tab-pane fade show active" id="tracking" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <select class="form-select" id="parcelFilter" onchange="filterParcels()">
                        <option value="all">All Parcels</option>
                        <option value="pending">Pending</option>
                        <option value="retrieved">Retrieved</option>
                    </select>
                </div>
                <table class="table table-striped w-100">
                    <thead>
                        <tr>
                            <th>Tracking Number</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="parcelList">
                        <!-- link with fetch parcel php -->
                    </tbody>
                </table>
            </div>
        
            <!-- New Parcel Tab -->
           <!-- New Parcel Tab -->
            <div class="tab-pane fade" id="addparcel" role="tabpanel">
               <h3>New Parcel</h3>
               <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#detailsModal">Add New Parcel</button>
            </div>


<!-- Footer Section -->
<footer class="bg-light py-4 mt-5 border-top">
    <div class="container">
        <!-- Footer Top Section -->
        <div class="row">
            <!-- About Section -->
            <div class="col-md-6 mb-4 mb-md-0">
                <h5 class="mb-3">Perwira Parcel Center</h5>
                <p class="text-muted small">
                    A service of Universiti Tun Hussein Onn Malaysia<br>
                    Your trusted partner in parcel management and delivery services.
                </p>
            </div>
            <!-- Contact Us Section -->
            <div class="col-md-6">
                <h5 class="mb-3">Contact Us</h5>
                <ul class="list-unstyled text-muted small">
                    <li><i class="fas fa-envelope me-2"></i> info@perwiraparcel.uthm.edu.my</li>
                    <li><i class="fas fa-phone me-2"></i> +60 11-1589 5859</li>
                    <li><i class="fas fa-map-marker-alt me-2"></i> Jalan Desasiswa, Parit Sempadan Laut, 86400 Parit Raja, Johor, Malaysia</li>
                </ul>
            </div>
        </div>

        <!-- Delivery Partners Section -->
        <div class="row mt-4">
            <div class="col-12">
                <h5 class="mb-3 text-center">Our Delivery Partners</h5>
                <div class="d-flex flex-wrap justify-content-center align-items-center px-3">
                    <img src="assets/posMalaysia.png" alt="Pos Malaysia" class="img-fluid mx-2 my-2" style="width: 80px; height: auto;">
                    <img src="assets/gdex.png" alt="GDEX" class="img-fluid mx-2 my-2" style="width: 80px; height: auto;">
                    <img src="assets/flashexpress.png" alt="Flash Express" class="img-fluid mx-2 my-2" style="width: 80px; height: auto;">
                    <img src="assets/shopeeExpress.jpeg" alt="Shopee Express" class="img-fluid mx-2 my-2" style="width: 80px; height: auto;">
                    <img src="assets/JNT.webp" alt="J&T" class="img-fluid mx-2 my-2" style="width: 80px; height: auto;">
                    <img src="assets/dhl.png" alt="DHL" class="img-fluid mx-2 my-2" style="width: 80px; height: auto;">
                    <img src="assets/fedex.png" alt="Fedex" class="img-fluid mx-2 my-2" style="width: 80px; height: auto;">
                    <img src="assets/ninjavan.png" alt="Ninjavan" class="img-fluid mx-2 my-2" style="width: 80px; height: auto;">
                </div>
            </div>
        </div>

        <!-- Footer Bottom Section -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                <p class="text-muted small mb-0">&copy; 2023 Perwira Parcel Center. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>       

<!-- Edit Parcel Modal -->
<div class="modal fade" id="editParcelModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editParcelForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Parcel</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="originalTrackingNumber" id="edit-original-tracking">
          <div class="mb-2">
            <label>IC No</label>
            <input type="text" class="form-control" name="ICNo" id="edit-icno" required>
          </div>
          <div class="mb-2">
            <label>Tracking Number</label>
            <input type="text" class="form-control" name="trackingNumber" id="edit-tracking" required>
          </div>
          <div class="mb-2">
            <label>Weight</label>
            <input type="number" class="form-control" name="weight" id="edit-weight" required>
          </div>
          <div class="mb-2">
            <label>Size</label>
            <input type="text" class="form-control" name="size" id="edit-size" required>
          </div>
          <div class="mb-2">
            <label>Delivery Location</label>
            <input type="text" class="form-control" name="deliveryLocation" id="edit-location" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Save Changes</button>
        </div>
      </div>
    </form>
  </div>
</div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function redirectToLandingPage() {
        window.location.href = 'landingpage.html'; // Redirect to the landing page
    }

function deleteParcel(trackingNumber) {
    if (confirm("Are you sure you want to delete parcel " + trackingNumber + "?")) {
        fetch('delete-parcel.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'trackingNumber=' + encodeURIComponent(trackingNumber)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Parcel " + trackingNumber + " has been deleted.");
                // Remove the row from the table
                const rows = document.querySelectorAll("#parcelList tr");
                rows.forEach(row => {
                    if (row.children[0].textContent === trackingNumber) {
                        row.remove();
                    }
                });
            } else {
                alert("Error deleting parcel: " + data.error);
            }
        })
        .catch(error => {
            alert("Request failed: " + error);
        });
    }
}


    function filterParcels() {
        const filter = document.getElementById("parcelFilter").value;
        const rows = document.querySelectorAll("#parcelList tr");
        rows.forEach(row => {
            const status = row.children[1].textContent.toLowerCase();
            if (filter === "all") {
                row.style.display = "";
            } else if (status === filter) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }


function showEditModal(button) {
  document.getElementById('edit-original-tracking').value = button.dataset.tracking;
  document.getElementById('edit-tracking').value = button.dataset.tracking;
  document.getElementById('edit-icno').value = button.dataset.icno;
  document.getElementById('edit-weight').value = button.dataset.weight;
  document.getElementById('edit-size').value = button.dataset.size;
  document.getElementById('edit-location').value = button.dataset.location;

  const modal = new bootstrap.Modal(document.getElementById('editParcelModal'));
  modal.show();
}

document.getElementById('editParcelForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const formData = new FormData(this);

  fetch('update-parcel.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.text())
  .then(result => {
    alert('Parcel updated!');
    location.reload();
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Update failed.');
  });
});

    function loadParcels() {
    fetch('fetch-parcel.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('parcelList').innerHTML = data;
        })
        .catch(error => console.error('Error fetching parcels:', error));
}

// Call this function on page load
window.onload = loadParcels;

function showParcelDetails(button) {
  // Get data attributes from clicked button
  const trackingNumber = button.getAttribute('data-tracking');
  const weight = button.getAttribute('data-weight');
  const size = button.getAttribute('data-size');
  const location = button.getAttribute('data-location');

  // Fill the modal input fields
  document.getElementById('outputTrackingNumber').value = trackingNumber;
  document.getElementById('outputParcelWeight').value = weight;
  document.getElementById('outputDeliveryLocation').value = location;

  // Set parcel size radio buttons
  document.getElementById('outputSizeLight').checked = (size === 'S');
  document.getElementById('outputSizeMedium').checked = (size === 'M');
  document.getElementById('outputSizeHeavy').checked = (size === 'L');

  // Show the modal using Bootstrap's modal API
  const outputModal = new bootstrap.Modal(document.getElementById('outputDetailsModal'));
  outputModal.show();
}




</script>

</body>    
</html>
