<?php
session_start();
require_once 'db_config.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { header("Location: index.php"); exit; }

// Fetch product
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$p = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$p) { header("Location: index.php"); exit; }

$history = isset($_COOKIE['viewed_products']) ? json_decode($_COOKIE['viewed_products'], true) : [];
$history = array_filter($history, fn($i) => $i['id'] != $id);
array_unshift($history, ['id' => $id, 'name' => $p['name'], 'price' => $p['price']]);
$history = array_slice($history, 0, 5);
setcookie('viewed_products', json_encode($history), time() + (7 * 24 * 60 * 60), '/');


// Add to cart from detail page
if (isset($_POST['add_to_cart'])) {
    $qty = max(1, intval($_POST['qty'] ?? 1));
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['qty'] += $qty;
    } else {
        $_SESSION['cart'][$id] = [
            'id'    => $p['id'],
            'name'  => $p['name'],
            'price' => $p['price'],
            'image' => $p['image'],
            'qty'   => $qty
        ];
    }
    header("Location: cart.php");
    exit;
}
?>
<?php include 'header.php'; ?>

<div class="container section">
    <p style="margin-bottom:1.5rem;">
        <a href="index.php" style="color:var(--primary-dark);">← Back to Shop</a>
    </p>

    <div class="product-detail">
        <!-- Product Image -->
        <div class="img-container">
            <img src="uploads/<?= htmlspecialchars($p['image']) ?>"
                 alt="<?= htmlspecialchars($p['name']) ?>"
                 onerror="this.src='https://images.unsplash.com/photo-1490750967868-88aa4f44baee?w=600&h=500&fit=crop'">
        </div>

        <!-- Product Details -->
        <div class="details">
            <h1><?= htmlspecialchars($p['name']) ?></h1>

            <div class="price"><?= number_format($p['price'], 2) ?> SAR</div>

            <?php if ($p['quantity'] > 0): ?>
                <span class="stock in-stock">✅ In Stock (<?= $p['quantity'] ?> available)</span>
            <?php else: ?>
                <span class="stock out-stock">❌ Out of Stock</span>
            <?php endif; ?>

            <p class="description"><?= nl2br(htmlspecialchars($p['description'])) ?></p>

            <?php if ($p['quantity'] > 0): ?>
            <form method="POST">
                <div class="quantity-selector">
                    <label style="font-weight:600; margin-right:0.5rem;">Quantity:</label>
                    <button type="button" onclick="changeQty(-1)">−</button>
                    <input type="number" name="qty" id="qtyInput" value="1" min="1" max="<?= $p['quantity'] ?>">
                    <button type="button" onclick="changeQty(1)">+</button>
                </div>
                <div style="display:flex; gap:1rem; flex-wrap:wrap;">
                    <button type="submit" name="add_to_cart" class="btn btn-success" style="padding:0.9rem 2rem; font-size:1rem;">
                        🛒 Add to Cart
                    </button>
                    <a href="cart.php" class="btn btn-outline" style="padding:0.9rem 2rem; font-size:1rem;">
                        View Cart
                    </a>
                </div>
            </form>
            <?php else: ?>
                <p style="color:var(--danger); font-weight:600;">This product is currently unavailable.</p>
                <a href="index.php" class="btn btn-outline" style="margin-top:1rem;">← Continue Shopping</a>
            <?php endif; ?>

            <!-- Help Section -->
            <div class="detail-help">
                <h4>💡 Need Help?</h4>
                <p>📞 Call us: <a href="tel:+966133334444">+966 13 333 4444</a></p>
                <p>✉️ Email: <a href="mailto:info@bloomflowers.sa">info@bloomflowers.sa</a></p>
                <p>🕐 Delivery: Same day if ordered before 2 PM</p>
                <p><a href="contact.php" style="color:var(--primary-dark); font-weight:600;">→ Contact Us</a></p>
            </div>
        </div>
    </div>
</div>

<script>
function changeQty(delta) {
    const input = document.getElementById('qtyInput');
    const max = parseInt(input.max);
    let val = parseInt(input.value) + delta;
    if (val < 1) val = 1;
    if (val > max) val = max;
    input.value = val;
}
</script>

<style>
.detail-help {
    background: var(--primary-light);
    border-radius: var(--radius);
    padding: 1.2rem 1.5rem;
    margin-top: 2rem;
    border-left: 4px solid var(--primary-dark);
}
.detail-help h4 {
    font-family: 'Inter', sans-serif;
    margin-bottom: 0.7rem;
    font-size: 1rem;
}
.detail-help p {
    font-size: 0.9rem;
    color: var(--text-light);
    margin-bottom: 0.3rem;
}
.detail-help a { color: var(--primary-dark); }
</style>

<?php include 'footer.php'; ?>
