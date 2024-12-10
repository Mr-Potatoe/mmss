<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="<?php echo BASE_URL; ?>">MotoService</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>/modules/rider/dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" role="button" 
                       data-bs-toggle="dropdown">
                        Service Requests
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="<?php echo BASE_URL; ?>/modules/rider/service-requests/create.php">
                                New Request
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?php echo BASE_URL; ?>/modules/rider/service-requests/history.php">
                                Request History
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="maintenanceDropdown" role="button" 
                       data-bs-toggle="dropdown">
                        Maintenance
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="<?php echo BASE_URL; ?>/modules/rider/maintenance/schedule.php">
                                Schedule Service
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?php echo BASE_URL; ?>/modules/rider/maintenance/history.php">
                                Schedule History
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" 
                       data-bs-toggle="dropdown">
                        <?php echo htmlspecialchars($_SESSION['fullname']); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="<?php echo BASE_URL; ?>/modules/auth/logout.php">
                                Logout
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav> 