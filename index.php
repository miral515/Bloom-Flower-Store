<?php
session_start();
require_once 'db_config.php';

// Add to cart
if (isset($_POST['add_to_cart'])) {
    $pid = intval($_POST['product_id']);
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    if (isset($_SESSION['cart'][$pid])) {
        $_SESSION['cart'][$pid]['qty']++;
    } else {
        $stmt = $conn->prepare("SELECT id, name, price, image FROM products WHERE id = ?");
        $stmt->bind_param("i", $pid);
        $stmt->execute();
        $p = $stmt->get_result()->fetch_assoc();
        if ($p) {
            $_SESSION['cart'][$pid] = [
                'id' => $p['id'],
                'name' => $p['name'],
                'price' => $p['price'],
                'image' => $p['image'],
                'qty' => 1
            ];
        }
        $stmt->close();
    }
    header("Location: index.php#products");
    exit;
}

$products = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>
<?php include 'header.php'; ?>

<div class="hero">
    <h1>Beautiful Flowers,<br>Delivered Fresh</h1>
    <p>Discover our handcrafted bouquets and arrangements for every special moment in life.</p>
    <a href="#products" class="btn-primary">Shop Now</a>
</div>

<div class="section" id="products">
    <div class="container">
        <h2 class="section-title">Our <span>Collection</span></h2>
        <div class="product-grid">
            <?php while ($p = $products->fetch_assoc()): ?>
            <div class="product-card">
                <a href="product_details.php?id=<?= $p['id'] ?>">
                    <div class="img-wrap">
                        <img src="uploads/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>"
                             onerror="this.src='https://images.unsplash.com/photo-1490750967868-88aa4f44baee?w=400&h=300&fit=crop'">
                    </div>
                </a>
                <div class="info">
                    <h3><?= htmlspecialchars($p['name']) ?></h3>
                    <p class="desc"><?= htmlspecialchars($p['description']) ?></p>
<p class="price"><?= number_format($p['price'], 2) ?> SAR</p>                </div>
                <form method="POST">
                    <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                    <button type="submit" name="add_to_cart" class="btn-add">🛒 Add to Cart</button>
                </form>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
