<?php
session_start();
require_once 'db_config.php';

$message = '';

if (isset($_POST['update_qty'])) {
    $pid = intval($_POST['product_id']);
    $action = $_POST['update_qty']; // نأخذ القيمة من الزر نفسه
    
    if (isset($_SESSION['cart'][$pid])) {
        if ($action === '-') {
            $_SESSION['cart'][$pid]['qty'] = max(1, $_SESSION['cart'][$pid]['qty'] - 1);
        } else if ($action === '+') {
            $_SESSION['cart'][$pid]['qty'] += 1;
        }
    }
    header("Location: cart.php");
    exit;
}

// Remove single item
if (isset($_POST['remove_item'])) {
    $pid = intval($_POST['product_id']);
    unset($_SESSION['cart'][$pid]);
    header("Location: cart.php");
    exit;
}

// Delete all
if (isset($_POST['delete_all'])) {
    $_SESSION['cart'] = [];
    header("Location: cart.php");
    exit;
}

// Buy
if (isset($_POST['buy'])) {
    $_SESSION['cart'] = [];
    $message = 'success';
}

$cart = $_SESSION['cart'] ?? [];
$total = 0;
foreach ($cart as $item) $total += $item['price'] * $item['qty'];
?>
<?php include 'header.php'; ?>

<div class="container section">
    <h2 class="section-title">Shopping <span>Cart</span></h2>

    <?php if ($message === 'success'): ?>
        <div class="alert alert-success">✅ Order placed successfully! Thank you for your purchase.</div>
    <?php endif; ?>

    <?php if (empty($cart)): ?>
        <div class="empty-cart">
            <h2>🛒 Your cart is empty</h2>
            <p style="color:var(--text-light); margin-bottom:1.5rem;">Add some beautiful flowers to get started!</p>
            <a href="index.php" class="btn btn-primary">Continue Shopping</a>
        </div>
    <?php else: ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart as $item): ?>
                <tr>
                    <td>
                        <img src="uploads/<?= htmlspecialchars($item['image']) ?>" alt="" class="cart-img"
                             onerror="this.src='https://images.unsplash.com/photo-1490750967868-88aa4f44baee?w=100&h=100&fit=crop'">
                    </td>
                    <td><strong><?= htmlspecialchars($item['name']) ?></strong></td>
                    <td><?= number_format($item['price'], 2) ?> SAR</td>
                    <td>
                        <form method="POST" class="qty-control">
                            <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                            <button type="submit" name="update_qty" value="-">−</button>
                            <span><?= $item['qty'] ?></span>
                            <button type="submit" name="update_qty" value="+">+</button>
                        </form>
                    </td>
                    <td><strong><?= number_format($item['price'] * $item['qty'], 2) ?> SAR</strong></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                            <button type="submit" name="remove_item" class="btn btn-danger btn-sm" title="Remove">✕</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="cart-summary">
            <h3>Order Summary</h3>
            <div class="row"><span>Subtotal</span><span><?= number_format($total, 2) ?> SAR</span></div>
            <div class="row"><span>Delivery</span><span style="color:var(--accent-dark);font-weight:600;">Free</span></div>
            <div class="row total"><span>Total</span><span><?= number_format($total, 2) ?> SAR</span></div>
            <div class="cart-actions">
                <form method="POST"><button type="submit" name="buy" class="btn btn-success">✓ Buy Now</button></form>
                <form method="POST"><button type="submit" name="delete_all" class="btn btn-danger">🗑 Delete All</button></form>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>