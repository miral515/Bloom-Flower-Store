<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bloom - Flower Store</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="navbar">
    <div class="container">
        <a href="index.php" class="logo">Bloom<span>.</span></a>
        <nav>
            <a href="index.php">Home</a>
            <a href="index.php#products">Shop</a>
            <a href="contact.php">Contact</a>
            <a href="admin/login.php">Admin</a>
            <a href="cart.php" class="cart-icon">🛒
                <?php
                $cartCount = 0;
                if (isset($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $item) $cartCount += $item['qty'];
                }
                if ($cartCount > 0): ?>
                    <span class="badge"><?= $cartCount ?></span>
                <?php endif; ?>
            </a>
        </nav>
    </div>
</div>
