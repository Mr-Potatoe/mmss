<footer class="footer mt-auto py-3 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>Quick Actions</h5>
                <ul class="list-unstyled">
                    <li><a href="<?php echo BASE_URL; ?>/modules/rider/service-requests/create.php" class="text-muted">Request Service</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/modules/rider/maintenance/schedule.php" class="text-muted">Schedule Maintenance</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/modules/rider/service-requests/history.php" class="text-muted">View History</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Support</h5>
                <ul class="list-unstyled text-muted">
                    <li>Emergency: (123) 456-7890</li>
                    <li>Email: support@motoservice.com</li>
                    <li>Service Hours: 8AM - 6PM</li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Account</h5>
                <ul class="list-unstyled">
                    <li><a href="<?php echo BASE_URL; ?>/modules/rider/dashboard.php" class="text-muted">Dashboard</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/modules/auth/logout.php" class="text-muted">Logout</a></li>
                </ul>
            </div>
        </div>
        <hr>
        <div class="text-center text-muted">
            <small>&copy; <?php echo date('Y'); ?> MotoService Rider Portal</small>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> 