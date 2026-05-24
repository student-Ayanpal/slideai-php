<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . " - SlideAI" : "SlideAI - AI Presentation Generator"; ?></title>
    
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Custom Global Styles -->
    <link rel="stylesheet" href="assets/css/global.css?v=<?php echo time(); ?>">
    
    <?php if(isset($extraStyles)) echo $extraStyles; ?>
</head>
<body>

<!-- Premium Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">SlideAI</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="index.php#features">Features</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php#how-it-works">How it Works</a>
                </li>
                <li class="nav-item ms-lg-3">
                    <a href="generator.php" class="btn btn-glow">Get Started</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
