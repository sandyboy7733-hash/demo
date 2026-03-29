<?php
require_once 'db.php';

$category = isset($_GET['cat']) ? $conn->real_escape_string($_GET['cat']) : '';
$search   = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';

$where = 'WHERE 1=1';
if ($category) $where .= " AND category = '$category'";
if ($search)   $where .= " AND (name LIKE '%$search%' OR description LIKE '%$search%')";

$result = $conn->query("SELECT * FROM products $where ORDER BY created_at DESC");
$categories_res = $conn->query("SELECT DISTINCT category FROM products ORDER BY category");

$categories = [];
while ($row = $categories_res->fetch_assoc()) {
    $categories[] = $row['category'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Products — TITAN</title>
  <link rel="stylesheet" href="style.css">

  <style>
    .products-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      padding: 20px;
    }

    .product-card {
      background: #fff;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      transition: 0.3s;
    }

    .product-card:hover {
      transform: translateY(-5px);
    }

    .product-card img {
      width: 100%;
      height: 220px;
      object-fit: cover;
    }

    .product-body {
      padding: 15px;
    }

    .product-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 10px;
    }

    .filter-bar a {
      margin-right: 10px;
      text-decoration: none;
      padding: 6px 12px;
      background: #eee;
      border-radius: 5px;
      color: #333;
    }

    .filter-bar a:hover {
      background: #333;
      color: #fff;
    }

    .page-hero {
      text-align: center;
      padding: 30px;
    }

    .page-hero input {
      padding: 8px;
      width: 200px;
    }

    .page-hero button {
      padding: 8px 12px;
    }
  </style>
</head>

<body>

<nav class="navbar">
  <a href="index.html" class="nav-logo">TIT<span>AN</span></a>
  <ul class="nav-links">
    <li><a href="index.html">Home</a></li>
    <li><a href="products.php" class="active">Products</a></li>
    <li><a href="contact.php">Contact</a></li>
  </ul>
</nav>

<!-- HERO -->
<div class="page-hero">
  <h1>Premium Products</h1>

  <form method="GET">
    <input type="text" name="q" placeholder="Search..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Search</button>
  </form>
</div>

<!-- FILTER -->
<div class="filter-bar">
  <a href="products.php">All</a>

  <?php foreach ($categories as $cat): ?>
    <a href="products.php?cat=<?= urlencode($cat) ?>">
      <?= htmlspecialchars($cat) ?>
    </a>
  <?php endforeach; ?>
</div>

<!-- PRODUCTS -->
<div class="products-grid">

<?php if ($result && $result->num_rows > 0): ?>
    <?php while ($p = $result->fetch_assoc()): ?>

        <div class="product-card">

            <!-- ✅ FIXED IMAGE PATH -->
            <img src="/titan_classic/<?= htmlspecialchars(trim($p['image'])) ?>" 
                 alt="<?= htmlspecialchars($p['name']) ?>">

            <div class="product-body">
                <h3><?= htmlspecialchars($p['name']) ?></h3>
                <p><?= htmlspecialchars($p['description']) ?></p>

                <div class="product-footer">
                    <span>₹<?= number_format($p['price'], 0) ?></span>
                    <button onclick="addToCart(this)">Add to Cart</button>
                </div>
            </div>

        </div>

    <?php endwhile; ?>
<?php else: ?>
    <h2 style="text-align:center;">No products found</h2>
<?php endif; ?>

</div>

<script>
function addToCart(btn){
  btn.innerText = "Added!";
  setTimeout(()=> btn.innerText="Add to Cart", 2000);
}
</script>

</body>
</html>