<?php
// Guard: only logged-in staff may view this page. Must come before any output.
session_start();
if (!isset($_SESSION['staffID'])) {
    header('Location: staff-login.html');
    exit;
}
$staffName = $_SESSION['username'] ?? 'Staff';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPC Staff Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="assets/Icon Web.ico" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
</head>
<body class="staff-dashboard-body">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg staff-navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="#" style="color: #fff; font-family: 'Poppins', sans-serif;">
                PERWIRA PARCEL CENTER
                <img src="assets/Icon Web.ico" alt="Parcel Icon" class="me-2" style="width: 30px; height: 30px;">
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <button class="btn btn-link nav-link" onclick="redirectToLandingPage()" style="
                            background: linear-gradient(135deg, #667eea, #764ba2);
                            border: none; border-radius: 25px; padding: 8px 20px;
                            color: white; font-weight: 500;">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Dashboard Container -->
    <div class="container mt-4">
        <h2 class="mb-4" style="font-weight: 600;">Welcome <?= htmlspecialchars($staffName) ?>!</h2>

        <h5>Parcel Management</h5>
        <p class="text-muted">Parcels registered by receivers. Mark a parcel retrieved once the student collects it.</p>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <select class="form-select w-auto" id="parcelFilter" onchange="filterParcels()">
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
                <!-- populated by fetch-parcel.php -->
            </tbody>
        </table>
    </div>

    <!-- Parcel Details Modal -->
    <div class="modal fade" id="outputDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Parcel Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Tracking Number:</strong> <span id="d-tracking"></span></p>
                    <p><strong>Receiver IC:</strong> <span id="d-icno"></span></p>
                    <p><strong>Receiver Name:</strong> <span id="d-name"></span></p>
                    <p><strong>Platform:</strong> <span id="d-platform"></span></p>
                    <p><strong>Description:</strong> <span id="d-description"></span></p>
                    <div id="d-photo-wrap" class="mt-2"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-light py-4 mt-5 border-top">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-4 mb-md-0">
                    <h5 class="mb-3">Perwira Parcel Center</h5>
                    <p class="text-muted small">
                        A service of Universiti Tun Hussein Onn Malaysia<br>
                        Your trusted partner in parcel management and delivery services.
                    </p>
                </div>
                <div class="col-md-6">
                    <h5 class="mb-3">Contact Us</h5>
                    <ul class="list-unstyled text-muted small">
                        <li><i class="fas fa-envelope me-2"></i> info@perwiraparcel.uthm.edu.my</li>
                        <li><i class="fas fa-phone me-2"></i> +60 11-1589 5859</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i> Jalan Desasiswa, Parit Sempadan Laut, 86400 Parit Raja, Johor, Malaysia</li>
                    </ul>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <p class="text-muted small mb-0">&copy; Perwira Parcel Center. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function redirectToLandingPage() {
            window.location.href = 'landingpage.html';
        }

        function loadParcels() {
            fetch('fetch-parcel.php')
                .then(response => response.text())
                .then(data => { document.getElementById('parcelList').innerHTML = data; })
                .catch(error => console.error('Error fetching parcels:', error));
        }

        function filterParcels() {
            const filter = document.getElementById("parcelFilter").value;
            const rows = document.querySelectorAll("#parcelList tr");
            rows.forEach(row => {
                if (!row.children[1]) return;
                const status = row.children[1].textContent.trim().toLowerCase();
                row.style.display = (filter === "all" || status === filter) ? "" : "none";
            });
        }

        function deleteParcel(trackingNumber) {
            if (!confirm("Are you sure you want to delete parcel " + trackingNumber + "?")) return;
            fetch('delete-parcel.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'trackingNumber=' + encodeURIComponent(trackingNumber)
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) { alert("Parcel " + trackingNumber + " deleted."); loadParcels(); }
                else { alert("Error deleting parcel: " + data.error); }
            })
            .catch(error => alert("Request failed: " + error));
        }

        function markRetrieved(trackingNumber) {
            if (!confirm("Mark parcel " + trackingNumber + " as retrieved?")) return;
            fetch('update-parcel.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'trackingNumber=' + encodeURIComponent(trackingNumber)
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) { alert("Parcel marked as retrieved."); loadParcels(); }
                else { alert("Error: " + data.error); }
            })
            .catch(error => alert("Request failed: " + error));
        }

        function showParcelDetails(button) {
            document.getElementById('d-tracking').textContent = button.dataset.tracking || '';
            document.getElementById('d-icno').textContent = button.dataset.icno || '';
            document.getElementById('d-name').textContent = button.dataset.name || '';
            document.getElementById('d-platform').textContent = button.dataset.platform || '';
            document.getElementById('d-description').textContent = button.dataset.description || '';

            const wrap = document.getElementById('d-photo-wrap');
            if (button.dataset.image) {
                wrap.innerHTML = '<img src="' + button.dataset.image + '" alt="parcel photo" style="max-width:100%;border-radius:8px;">';
            } else {
                wrap.innerHTML = '<span class="text-muted small">No photo provided.</span>';
            }

            new bootstrap.Modal(document.getElementById('outputDetailsModal')).show();
        }

        window.onload = loadParcels;
    </script>
</body>
</html>
