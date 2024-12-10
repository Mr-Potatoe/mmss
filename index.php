<?php
session_start();
require_once 'config/database.php';
require_once 'models/ServiceType.php';

// Get active service types
$serviceType = new ServiceType($conn);
$services = $serviceType->getActiveServices();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MotoService - Professional Motorcycle Services</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-brand">
            <a href="index.php">
                <h1>MotoService</h1>
            </a>
        </div>
        <div class="nav-links">
            <a href="#services" class="nav-link">Services</a>
            <a href="#about" class="nav-link">About</a>
            <a href="#contact" class="nav-link">Contact</a>
            <a href="modules/auth/login.php" class="nav-link">Login</a>
            <a href="modules/auth/register.php" class="nav-link btn-register">Register</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero">
        <div class="hero-content">
            <h1>Professional Motorcycle Service</h1>
            <p>Expert maintenance and repair services for your motorcycle</p>
            <div class="hero-buttons">
                <a href="modules/auth/register.php" class="cta-button primary">Get Started</a>
                <a href="modules/auth/login.php" class="cta-button secondary">Login</a>
            </div>
        </div>
    </header>

    <!-- Services Section -->
    <section class="services" id="services">
        <div class="container">
            <h2>Our Services</h2>
            <div class="service-grid">
                <?php foreach ($services as $service): ?>
                    <div class="service-card">
                        <h3><?php echo htmlspecialchars($service['name']); ?></h3>
                        <p><?php echo htmlspecialchars($service['description'] ?? 'Professional motorcycle service'); ?></p>
                        <a href="modules/auth/register.php" class="service-link">Book Now</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="about">
        <div class="container">
            <h2>Why Choose Us</h2>
            <div class="feature-grid">
                <div class="feature-card">
                    <h3>Expert Mechanics</h3>
                    <p>Our team of certified mechanics ensures your motorcycle gets the best care possible.</p>
                </div>
                <div class="feature-card">
                    <h3>Quality Service</h3>
                    <p>We use only high-quality parts and follow manufacturer specifications.</p>
                </div>
                <div class="feature-card">
                    <h3>Convenient Booking</h3>
                    <p>Easy online scheduling and real-time service updates.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact" id="contact">
        <div class="container">
            <h2>Contact Us</h2>
            <div class="contact-content">
                <div class="contact-info">
                    <h3>Get in Touch</h3>
                    <p><strong>Address:</strong> 123 Motorcycle Street, City</p>
                    <p><strong>Phone:</strong> (123) 456-7890</p>
                    <p><strong>Email:</strong> contact@motoservice.com</p>
                    <p><strong>Hours:</strong> Monday - Saturday: 8:00 AM - 6:00 PM</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Quick Links</h3>
                <a href="#services">Services</a>
                <a href="#about">About Us</a>
                <a href="#contact">Contact</a>
                <a href="modules/auth/login.php">Login</a>
                <a href="modules/auth/register.php">Register</a>
            </div>
            <div class="footer-section">
                <h3>Services</h3>
                <a href="#services">Engine Oil Change</a>
                <a href="#services">Tire Replacement</a>
                <a href="#services">Full Maintenance</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> MotoService. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
