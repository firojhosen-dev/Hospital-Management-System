<?php
session_start();
require_once '../../../../config/config.php';

// Search keyword
$search = isset($_GET['q']) ? trim($_GET['q']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Products</title>
  <link rel="shortcut icon" href="../../../../public/assets/images/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css">
   
  <style>
@import url(https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600;700&display=swap);
/* light mood */
:root{
    --Primary: #0704a8ff;
    --NeutralBackground: #b7e8faff;
    --Text: #00010aff;
    --Highlight: #dc7d09ff;
    --Accent: #01a809ff;
}
/* Dark Mood */
:root{
    --PrimaryDark: #f35b04;
    --NeutralBackgroundDark: #03045e;
    --TextDark: #b7e8faff;
    --HighlightDark: #4331e0ff;
    --AccentDark: #f7bd04;
}
*{
    font-family: 'Rajdhani', sans-serif;
}
:root {
    --cardColor:  #94c6fb;
  --colorPrimary: #2563EB;  
  --colorAccent:  #10B981;  
  --bgColor:      #c7e0fa;  
  --colorText:    #0F172A; 
}
/* Fullscreen Loader */
#loader {
    position: fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    z-index:9999;
}

/* Loader Container */
.loader-logo {
    width:200px;
    height:200px;
    border:3px solid #3498db;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    position:relative;
    animation: spinBorder 2s linear infinite, borderColor 3s linear infinite;
    overflow:hidden;
}

/* Logo Image */
.loader-logo img {
    width:120px;
    height:auto;
    z-index:2;
    position:relative;
}

/* Scanning Effect */
.loader-logo::after {
    content:"";
    position:absolute;
    top:-50%;
    left:0;
    width:100%;
    height:50%;
    background:rgba(52,152,219,0.3);
    animation: scan 2s linear infinite;
    z-index:1;
}

/* Rotate border */
@keyframes spinBorder {
    0% { transform:rotate(0deg); }
    100% { transform:rotate(360deg); }
}

/* Border color change */
@keyframes borderColor {
    0%   { border-color:#3498db; }
    25%  { border-color:#e74c3c; }
    50%  { border-color:#2ecc71; }
    75%  { border-color:#f1c40f; }
    100% { border-color:#3498db; }
}

/* Scanning light animation */
@keyframes scan {
    0% { top:-50%; }
    100% { top:100%; }
}

body{
    font-family:Arial,sans-serif;
    background:var(--bgColor);
    color: var(--colorText);
    margin:0;
    padding:0;
}
.container{
    max-width:100%;
    margin:50px auto;
    padding:0 15px;
}
h2{text-align:center;margin-bottom:20px;color:#333;}

/* Header */
header{background:#fff;box-shadow:0 2px 6px rgba(0,0,0,0.1);padding:10px 20px;position:sticky;top:0;z-index:1000;}
.header-container{display:flex;align-items:center;justify-content:space-between;max-width:1200px;margin:auto;}
.logo{font-size:22px;font-weight:bold;color:#17a2b8;text-decoration:none;}
nav ul{list-style:none;margin-left:30px;padding:0;display:flex;gap:20px; text-align: right;}
nav ul li a{text-decoration:none;color:#333;font-weight:500;text-align: right;}
nav ul li a:hover{color:#17a2b8;}
.search-box{flex:1;margin:0 20px;justify-content: center;display: flex;
}
.search-box form{
    display:flex;
    width: 500px;
}
.search-box input{
    flex:1;padding:8px;
    border:1px solid #ccc;
    border-radius:5px 0 0 5px;
    max-width: 500px;
}
.search-box button{
    padding:8px 15px;
    border:none;
    background:#17a2b8;
    color:#fff;
    border-radius:0 5px 5px 0;
    cursor:pointer;
}
.icons{
    display:flex;
    gap:15px;
}
.icons i{
    font-size:20px;
    cursor:pointer;
    color:#333;
}
.menu-toggle{
    display:none;
    font-size:24px;
    cursor:pointer;
}

    @media(max-width:768px){
      nav{display:none;position:absolute;top:60px;left:0;width:100%;background:#fff;box-shadow:0 2px 6px rgba(0,0,0,0.2);}
      nav ul{flex-direction:column;align-items:center;padding:15px 0;}
      nav.active{display:block;}
      /* .search-box{display:none;} */
      .menu-toggle{display:block;}
    }

    /* Products */
    .product-cards{display:flex;flex-wrap:wrap;gap:20px; grid-template-columns: 1fr 1fr;}
.card-product{
    background:#fff;
    padding:15px;
    border-radius:10px;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
    flex:1 1 calc(10% - 20px);
    box-sizing:border-box;
    text-align:center;
}
    .card-product img{width:100%;height:150px;object-fit:cover;border-radius:5px;margin-bottom:10px;cursor:pointer;}
    .card-product h4{margin:5px 0;}
    .card-product p{margin:5px 0;color:#28a745;font-weight:bold;}
    .card-product button{margin-top:10px;padding:6px 10px;border:none;border-radius:5px;cursor:pointer;background:#17a2b8;color:#fff;}
    .card-product button:hover{background:#138496;}
    .card-product form button{margin-top:5px;width:100%;padding:6px 0;}
    @media(max-width:992px){.card-product{flex:1 1 calc(30% - 20px);}}
    @media(max-width:600px){.card-product{flex:1 1 calc(50% - 20px);}}


/* General Footer Styling */
.footer {
    background: #222;
    color: #fff;
    padding: 40px 0 10px;
}
.footer_container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    max-width: 1200px;
    margin: auto;
    padding: 0 20px;
}
.footer_section h2 {
    font-size: 18px;
    margin-bottom: 15px;
    border-bottom: 2px solid #f39c12;
    padding-bottom: 5px;
}
.footer_section p,
.footer_section a {
    font-size: 14px;
    color: #bbb;
    text-decoration: none;
}
.footer_section a:hover {
    color: #f39c12;
    transition: 0.3s;
}
.footer_section ul {
    list-style: none;
    padding: 0;
}
.footer_section ul li {
    margin-bottom: 10px;
}
.socials a {
    display: inline-block;
    margin-right: 10px;
    color: #bbb;
    font-size: 20px;
    border: 1px solid #ddd;
    padding: 2px 5px 2px 5px;
    border-radius: 50%;
}
.socials a:hover {
    color: #f39c12;
    border-color: #f39c12;
    transform: scale(1.1);
    transition: 0.3s ease;
}
/* Footer Bottom */
.footer_bottom {
    text-align: center;
    padding: 15px 0 5px;
    background: #111;
    font-size: 13px;
    margin-top: 20px;
}
.footer_bottom span {
    color: #f39c12;
}
.footer_bottom a{
    color: #22e625;
    text-decoration: none;
}
/* Responsive Adjustments */
@media (max-width: 768px) {
    .footer_container {
        grid-template-columns: 1fr 1fr;
    }
}
@media (max-width: 500px) {
    .footer_container {
        grid-template-columns: 1fr;
    }
    .footer_section {
        text-align: center;
    }
}

/* Custom Scrollbar */
::-webkit-scrollbar {
  width: 7px;            /* Scrollbar এর width */
}
::-webkit-scrollbar-track {
  background: #f1f1f1;     /* Track এর background */
}
::-webkit-scrollbar-thumb {
  background: linear-gradient(#0704a8ff, #dc7d09ff, #01a809ff);     /* Scrollbar এর রঙ */
  border-radius: 5px;      /* Round effect */
}
::-webkit-scrollbar-thumb:hover {
  background: #0704a8ff; 
}

/* Firefox Support */
* {
  scrollbar-width: thin;
  scrollbar-color: #0077cc #f1f1f1;
}
  </style>
</head>
<body>

<!-- HEADER -->
<header>
  <div class="header-container">
    <a href="#" class="logo">Pharmacy</a>
    <div class="menu-toggle"><i class="ri-menu-line"></i></div>
    <nav>
      <ul>
        <li><a href="#">Home</a></li>
        <li><a href="#">Shop</a></li>
        <li><a href="#">About</a></li>
        <li><a href="#">Contact</a></li>
        <li><a href="manage_order.php">Manage Order</a></li>
        <li><a href="my_order.php">My Order</a></li>
      </ul>
    </nav>
    <div class="icons">
      <a href="../auth/login.php"><i class="ri-user-line"></i></a>
      <a href="cart.php"><i class="ri-shopping-cart-line"></i></a>
      
    </div>
  </div>
</header>
<!-- Loader -->
<!-- <div id="loader">
  <div class="loader-logo">
    <img src="../../../../public/assets/images/logo.png" alt="Hospital Logo">
  </div>
</div> -->


<!-- PRODUCTS -->
<div class="container" id="loader_content">
  <div class="search-box">
      <form method="get" action="">
        <input type="text" name="q" placeholder="Search products..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit"><i class="ri-search-line"></i></button>
      </form>
    </div>
  <h2>All Products</h2>
  <div class="product-cards">
  <?php
  if($search != ''){
      $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ? ORDER BY id DESC");
      $param = "%".$search."%";
      $stmt->bind_param("s", $param);
      $stmt->execute();
      $res = $stmt->get_result();
  } else {
      $res = $conn->query("SELECT * FROM products ORDER BY id DESC");
  }

  if(!$res){
      die("❌ Query failed: ".$conn->error);
  }
  if($res->num_rows==0){
      echo "<p style='text-align:center;'>No products found.</p>";
  } else {
      while($row = $res->fetch_assoc()):
          $images = explode(',', $row['images']);
  ?>
  <!-- <a href=""></a> -->
    <div class="card-product" onclick="window.location='view_product.php?id=<?= $row['id'] ?>'">
      <?php if(!empty($images[0]) && file_exists("uploads/".$images[0])): ?>
      <img src="uploads/<?= $images[0] ?>" alt="<?= htmlspecialchars($row['name']) ?>">
      <?php endif; ?>
      <h4><?= htmlspecialchars($row['name']) ?></h4>
      <p>$<?= $row['price'] ?></p>

      <!-- Product Buttons -->
      <!-- <button onclick="window.location='edit_product.php?id=<?= $row['id'] ?>'">Edit</button> -->
      <!-- <button class="delete-btn" data-id="<?= $row['id'] ?>">Delete</button> -->

      <!-- Add to Cart & Buy Now -->
      <form action="cart.php" method="post">
          <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
          <button type="submit" name="add_to_cart">Add to Cart</button>
      </form>
      <form action="checkout.php" method="post">
          <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
          <button type="submit" name="buy_now">Buy Now</button>
      </form>
    </div>
  <?php endwhile; } ?>
  </div>
</div>
<!--? Footer -->
<main class="main_footer_section">
    <footer class="footer">
        <div class="footer_container">
            <!-- About -->
            <div class="footer_section about">
                <h2>About Us</h2>
                <p>We provide top-notch services with passion and dedication. Our goal is to bring your vision to life.</p>
                <div class="socials">
                    <a href="#"><i class="ri-facebook-fill"></i></a>
                    <a href="#"><i class="ri-instagram-line"></i></a>
                    <a href="#"><i class="ri-twitter-x-line"></i></a>
                    <a href="#"><i class="ri-github-line"></i></a>
                </div>
            </div>
            <!-- Quick Links -->
            <div class="footer_section links">
                <h2>Quick Links</h2>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Services</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
            <!-- Services -->
            <div class="footer_section services">
                <h2>Services</h2>
                <ul>
                    <li><a href="#">Prescription Medicines</a></li>
                    <li><a href="#">(OTC) Medicines</a></li>
                    <li><a href="#">Health Consultation</a></li>
                    <li><a href="#">Home Delivery</a></li>
                </ul>
            </div>
            <!-- Contact -->
            <div class="footer_section contact">
                <h2>Creator</h2>
                <p>Firoj Hosen</p>
                <p> I am a professional web developer. Create a beautiful, fast and useful website and web app using modern technology. I always have interest in solving problems and learning something new.</p>
                <p><i class="ri-customer-service-2-line"></i> +880 1328477369</p>
                <p><i class="ri-mail-line"></i> firojhosendev@gmail.com</p>
            </div>
        </div>
        <div class="footer_bottom">
            <p>© <span id="year"></span> <a href="https://www.linkedin.com/in/firojhossendev">Firoj Hosen</a>|Hospital Management System | All Rights Reserved</p>
        </div>
    </footer>
</main>
<!-- <script src="../../../../public/assets/js/main.js"></script> -->
<script>
//? Hide loader after 5 seconds
setTimeout(function(){
    document.getElementById("loader").style.display="none";
    document.getElementById("loader_content").style.display="block";
}, 1000);
document.querySelectorAll('.delete-btn').forEach(btn=>{
    btn.addEventListener('click', function(){
        if(!confirm('Are you sure to delete this product?')) return;
        const id = this.dataset.id;
        fetch('delete_product.php',{
            method:'POST',
            headers:{'Content-Type':'application/x-www-form-urlencoded'},
            body:'id='+id
        })
        .then(res=>res.json())
        .then(data=>{
            alert(data.msg);
            if(data.status==1) location.reload();
        });
    });
});
//? Responsive menu toggle
document.querySelector('.menu-toggle').addEventListener('click', ()=>{
  document.querySelector('nav').classList.toggle('active');
});
</script>
</body>
</html>