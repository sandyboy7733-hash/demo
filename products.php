<?php
// =============================================
// TITAN — Products Page
// =============================================
require_once 'db.php';

$category = isset($_GET['cat']) ? $conn->real_escape_string($_GET['cat']) : '';
$search   = isset($_GET['q'])   ? $conn->real_escape_string($_GET['q'])   : '';

$where = 'WHERE 1=1';
if ($category) $where .= " AND category = '$category'";
if ($search)   $where .= " AND (name LIKE '%$search%' OR description LIKE '%$search%')";

$result = $conn->query("SELECT * FROM products $where ORDER BY created_at DESC");

$categories_res = $conn->query("SELECT DISTINCT category FROM products");
$categories = [];
while ($row = $categories_res->fetch_assoc()) {
  $categories[] = $row['category'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Products — TITAN</title>
  <link rel="stylesheet" href="style.css"/>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
  <a href="index.html" class="nav-logo">TIT<span>AN</span></a>
  <ul class="nav-links">
    <li><a href="index.html">Home</a></li>
    <li><a href="products.php" class="active">Products</a></li>
    <li><a href="contact.php">Contact</a></li>
  </ul>
  <div class="nav-actions">
    <a href="login.php" class="btn-nav btn-outline">Login</a>
    <a href="register.php" class="btn-nav btn-solid">Register</a>
  </div>
</nav>

<!-- HERO -->
<div class="page-hero">
  <h1>Premium Products</h1>
</div>

<!-- PRODUCTS -->
<div class="products-section">

  <!-- CATEGORY FILTER -->
  <div class="filter-bar">
    <a href="products.php" class="filter-btn <?= !$category ? 'active' : '' ?>">All</a>
    <?php foreach ($categories as $cat): ?>
      <a href="products.php?cat=<?= urlencode($cat) ?>" 
         class="filter-btn <?= $category === $cat ? 'active' : '' ?>">
        <?= htmlspecialchars($cat) ?>
      </a>
    <?php endforeach; ?>
  </div>

  <!-- GRID -->
  <?php if ($result && $result->num_rows > 0): ?>
    <div class="products-grid">

      <?php while ($p = $result->fetch_assoc()): ?>
        <div class="product-card">

          <!-- ✅ IMAGE FRAME -->
          <div class="product-img" style="padding:15px;">
            <div style="width:200px;height:200px;background:#111;border-radius:12px;
                        display:flex;align-items:center;justify-content:center;margin:auto;
                        border:1px solid #333;">

              <!-- ✅ IMAGE -->
              <img src="<?= htmlspecialchars($p['image']) ?>" 
                   style="max-width:150px;max-height:150px;object-fit:contain;">
            </div>

            <!-- BADGE -->
            <?php if (!empty($p['badge'])): ?>
              <span class="product-badge"><?= htmlspecialchars($p['badge']) ?></span>
            <?php endif; ?>
          </div>

          <!-- BODY -->
          <div class="product-body">
            <div class="product-cat"><?= htmlspecialchars($p['category'] ?? '') ?></div>
            <div class="product-name"><?= htmlspecialchars($p['name']) ?></div>
            <div class="product-desc"><?= htmlspecialchars($p['description'] ?? '') ?></div>

            <div class="product-footer">
              <div class="product-price">
                <?php if (!empty($p['old_price'])): ?>
                  <small style="text-decoration:line-through;color:gray;">
                    ₹<?= number_format($p['old_price'],0) ?>
                  </small>
                <?php endif; ?>
                <strong>₹<?= number_format($p['price'],0) ?></strong>
              </div>

              <button class="btn-cart" 
                onclick="addToCart(this, '<?= htmlspecialchars($p['name']) ?>')">
                Add to Cart
              </button>
            </div>
          </div>

        </div>
      <?php endwhile; ?>

    </div>
  <?php else: ?>
    <p style="text-align:center;color:white;">No products found</p>
  <?php endif; ?>

</div>

<!-- FOOTER -->
<footer>
  <p style="text-align:center;">© 2025 TITAN</p>
</footer>

<script>
function addToCart(btn, name){
  btn.textContent = '✓ Added!';
  btn.style.background = 'gold';
  btn.style.color = 'black';

  setTimeout(() => {
    btn.textContent = 'Add to Cart';
    btn.style.background='';
    btn.style.color='';
  }, 2000);
}
</script>

</body>
</html>