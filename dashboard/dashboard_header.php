<?php
// Start session and check if the user is logged in
session_start();

if (!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id']) || !isset($_SESSION['username']) || $_SESSION['logged_in'] != 1) {
    header('Location: login.php?login='.$_SESSION['logged_in']);
    exit;
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yourhiring Dashbaord</title>
    
<link rel='stylesheet prefetch' href='https://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.min.css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="icon" href="../images/logo.png">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>
    <header class="border-bottom">
        <nav class="navbar navbar-expand-lg bg-light bg-white">
            <div class="container">
                <div class="container-fluid justify-content-between d-flex align-items-center" style="flex: 1;">
                    <a class="navbar-brand" href="../index.php">
                        <img src="../images/logo.png" alt="Logo" width="200" height="72">
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="navbarSupportedContent" dir="rtl">
                    <ul class="navbar-nav mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link fs-5 <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>" aria-current="page" href="index.php">صفحة لوحة التحكم</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-5 <?php echo (basename($_SERVER['PHP_SELF']) == 'messages.php') ? 'active' : ''; ?>" href="messages.php">الرسائل المرسلة</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-5 <?php echo (basename($_SERVER['PHP_SELF']) == 'services.php') ? 'active' : ''; ?>" href="services.php">الخدمات</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-5 <?php echo (basename($_SERVER['PHP_SELF']) == 'events.php') ? 'active' : ''; ?>" href="events.php">أعلانات</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-5 <?php echo (basename($_SERVER['PHP_SELF']) == 'testimonials.php') ? 'active' : ''; ?>" href="testimonials.php">أراء العملاء</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>