<?php
// Guard: only logged-in receivers may view this page. Must come before output.
session_start();
if (!isset($_SESSION['icnumber'])) {
    header('Location: receiver-login.html');
    exit;
}
$receiverName = $_SESSION['username'] ?? 'Receiver';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>PPC Receiver Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="icon" href="assets/Icon Web.ico" type="image/x-icon" />
    <link rel="stylesheet" href="style.css" />
</head>
<body class="receiver-dashboard-body">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg receiver-navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="#" style="color: #fff; font-family: 'Poppins', sans-serif;">
                PERWIRA PARCEL CENTER
                <img src="assets/Icon Web.ico" alt="Parcel Icon" class="me-2" style="width: 30px; height: 30px;" />
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <button class="btn btn-link nav-link dashboard-logout-btn" onclick="redirectToLandingPage()">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Dashboard Container -->
    <div class="container mt-4">
        <h2 class="mb-4" style="font-weight: 600;">
            Welcome <?= htmlspecialchars($receiverName) ?>!
        </h2>

        <ul class="nav nav-tabs" id="receiverDashboardTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="tracking-tab" data-bs-toggle="tab" data-bs-target="#tracking" type="button" role="tab">Track</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="myparcels-tab" data-bs-toggle="tab" data-bs-target="#myparcels" type="button" role="tab">My Parcels</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="add-tab" data-bs-toggle="tab" data-bs-target="#addparcel" type="button" role="tab">Add Parcel</button>
            </li>
        </ul>

        <div class="tab-content mt-3">
            <!-- Track Tab -->
            <div class="tab-pane fade show active" id="tracking" role="tabpanel">
                <h5>Track a parcel</h5>
                <p>Enter a tracking number to see its current status.</p>
                <div class="input-group mb-3">
                    <input type="text" id="trackingInput" class="form-control" placeholder="Enter tracking number" />
                    <button class="btn btn-primary" onclick="trackParcel()">Track</button>
                </div>
                <div id="trackResult"></div>
            </div>

            <!-- My Parcels Tab -->
            <div class="tab-pane fade" id="myparcels" role="tabpanel">
                <h5>My Parcels</h5>
                <p>Parcels you have registered.</p>
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Tracking Number</th>
                            <th>Platform</th>
                            <th>Description</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="myParcelsList">
                        <tr><td colspan="5" class="text-center text-muted">Loading…</td></tr>
                    </tbody>
                </table>
            </div>

            <!-- Add Parcel Tab -->
            <div class="tab-pane fade" id="addparcel" role="tabpanel">
                <h5>Register a new parcel</h5>
                <p>Bought something online? Register it here so the center can match it when it arrives.</p>
                <form action="add-parcel.php" method="POST" enctype="multipart/form-data" class="mt-3" style="max-width: 520px;">
                    <div class="mb-3">
                        <label for="platform" class="form-label">Platform</label>
                        <select class="form-select" name="platform" id="platform" required>
                            <option value="" selected disabled>Where did you buy it?</option>
                            <option value="Shopee">Shopee</option>
                            <option value="TikTok">TikTok</option>
                            <option value="Lazada">Lazada</option>
                            <option value="Temu">Temu</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="trackingNumber" class="form-label">Tracking Number</label>
                        <input type="text" class="form-control" name="trackingNumber" id="trackingNumber" placeholder="From your order / courier" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Item Description</label>
                        <textarea class="form-control" name="description" id="description" rows="3" placeholder="What is the item?" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="photo" class="form-label">Item Photo <span class="text-muted small">(optional, max 5 MB)</span></label>
                        <input type="file" class="form-control" name="photo" id="photo" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary">Register Parcel</button>
                </form>
            </div>
        </div>
    </div>

    <footer class="bg-white py-4 mt-5 border-top">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-4 mb-md-0">
                    <h5 class="mb-3 text-uthm">Perwira Parcel Center</h5>
                    <p class="text-muted small">
                        A service of Universiti Tun Hussein Onn Malaysia<br>
                        Your trusted partner in parcel management and delivery services.
                    </p>
                </div>
                <div class="col-md-6">
                    <h5 class="mb-3 text-uthm">Contact Us</h5>
                    <ul class="list-unstyled text-muted small">
                        <li><i class="fas fa-envelope me-2"></i>info@perwiraparcel.uthm.edu.my</li>
                        <li><i class="fas fa-phone me-2"></i>+60 11-1589 5859</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i>
                            Jalan Desasiswa, Parit Sempadan Laut, 86400 Parit Raja, Johor, Malaysia
                        </li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="copyright text-center text-muted small">
                <p class="mb-0">Made <i class="fas fa-heart text-danger"></i> by Group 7</p>
                &copy; All rights reserved.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function redirectToLandingPage() {
            window.location.href = 'landingpage.html';
        }

        function escapeHtml(s) {
            const d = document.createElement('div');
            d.textContent = s == null ? '' : s;
            return d.innerHTML;
        }

        function trackParcel() {
            const tracking = document.getElementById('trackingInput').value.trim();
            const target = document.getElementById('trackResult');
            if (!tracking) {
                target.innerHTML = '<div class="alert alert-warning">Please enter a tracking number.</div>';
                return;
            }

            fetch(`track-parcel.php?tracking=${encodeURIComponent(tracking)}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        const p = data.data;
                        const badge = p.status === 'retrieved' ? 'success' : 'warning';
                        target.innerHTML = `
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">${escapeHtml(p.trackingNumber)}</h5>
                                    <p class="mb-1"><strong>Platform:</strong> ${escapeHtml(p.platform)}</p>
                                    <p class="mb-1"><strong>Item:</strong> ${escapeHtml(p.description)}</p>
                                    <p class="mb-0"><strong>Status:</strong> <span class="badge bg-${badge}">${escapeHtml(p.status)}</span></p>
                                </div>
                            </div>`;
                    } else {
                        target.innerHTML = `<div class="alert alert-danger">${escapeHtml(data.message || 'Parcel not found.')}</div>`;
                    }
                })
                .catch(() => {
                    target.innerHTML = '<div class="alert alert-danger">Error fetching parcel data.</div>';
                });
        }

        function loadMyParcels() {
            fetch('my-parcels.php')
                .then(res => res.text())
                .then(html => { document.getElementById('myParcelsList').innerHTML = html; })
                .catch(() => {
                    document.getElementById('myParcelsList').innerHTML =
                        '<tr><td colspan="5" class="text-center text-danger">Could not load your parcels.</td></tr>';
                });
        }

        // Load the receiver's parcels when they open that tab (and once on start).
        document.getElementById('myparcels-tab').addEventListener('shown.bs.tab', loadMyParcels);
        window.addEventListener('load', loadMyParcels);
    </script>

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
