<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome | Clinic Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f7f6; }
        .hero-section { height: 100vh; display: flex; align-items: center; justify-content: center; }
        .welcome-card { border: none; border-radius: 25px; padding: 50px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); background: #fff; }
    </style>
</head>
<body>

<div class="hero-section">
    <div class="container text-center">
        <div class="welcome-card mx-auto" style="max-width: 500px;">
            <div class="mb-4">
                <i class="bi bi-hospital text-primary" style="font-size: 4rem;"></i>
            </div>
            <h1 class="fw-bold text-dark">Clinic Inventory System</h1>
            <p class="text-muted mb-5">Efficiently manage medical supplies, medicines, and equipment with role-based access.</p>
            
            <div class="d-grid gap-3">
                <a href="login.php" class="btn btn-primary btn-lg rounded-pill shadow">Log In</a>
                <a href="register.php" class="btn btn-outline-secondary btn-lg rounded-pill">Create Staff Account</a>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</body>
</html>