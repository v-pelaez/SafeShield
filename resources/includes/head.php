<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SafeShield</title>
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="icon" href="src/favicon.png" type="image/png" sizes="32x32">
    <?php
    if(isset($_SESSION['user'])){
    $bgColor = $_COOKIE[$_SESSION['user'].'_bg-color'] ?? '#f3f3f3';
    $headerColor = $_COOKIE[$_SESSION['user'].'_header-color'] ?? '#0063b5';
    $footerColor = $_COOKIE[$_SESSION['user'].'_footer-color'] ?? '#0063b5';
    }
    if(isset($_SESSION["role"]) && $_SESSION["role"] === "admin"){
    ?>

    <style>
        body {
            background-color: <?= htmlspecialchars($bgColor) ?>;
        }

        header {
            background-color: <?= htmlspecialchars($headerColor) ?>;
        }

        footer {
            background-color: <?= htmlspecialchars($footerColor) ?>;
        }
    </style>
    <?php } ?>
</head>

<?php

require_once __DIR__ . '/../includes/cookies.php';
 ?>

<body>