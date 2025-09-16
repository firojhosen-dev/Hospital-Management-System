<?php
session_start();
require_once '../../../../config/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Product</title>
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
/* Container */
.add_product_container {
  max-width: 1100px;
  margin: 30px auto;
  padding: 25px;
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
}

.add_product_container h2 {
  text-align: center;
  margin-bottom: 20px;
  font-size: 26px;
  color: #1976d2;
}

/* Form Layout */
.add_product_form {
  display: flex;
  gap: 30px;
  justify-content: space-between;
}

.left_side,
.right_side {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 15px;
}

/* Inputs & Textarea */
.add_product_input,
.add_product_textarea,
.add_product_form input[type="file"] {
  width: 100%;
  padding: 12px 15px;
  border: 1px solid #ddd;
  border-radius: 10px;
  font-size: 15px;
  outline: none;
  transition: border 0.3s ease, box-shadow 0.3s ease;
}

.add_product_input:focus,
.add_product_textarea:focus,
.add_product_form input[type="file"]:focus {
  border: 1px solid #1976d2;
  box-shadow: 0 0 5px rgba(25, 118, 210, 0.4);
}

/* Textarea */
.add_product_textarea {
  min-height: 120px;
  resize: vertical;
}

/* Button */
.add_product_button {
  background: #1976d2;
  color: #fff;
  padding: 12px;
  border: none;
  border-radius: 10px;
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
  transition: background 0.3s ease;
}

.add_product_button:hover {
  background: #125aa0;
}

/* Label */
.right_side label {
  font-weight: 600;
  color: #333;
  margin-bottom: 5px;
}

/* Responsive */
@media (max-width: 768px) {
  .add_product_form {
    flex-direction: column;
  }
}
</style>
</head>
<body>

<div class="add_product_container">
    <h2>Add New Product</h2>
    <form class="add_product_form" id="addForm" method="post" enctype="multipart/form-data">
        <div class="left_side">
            <input class="add_product_input" type="text" name="name" placeholder="Product Name" required>
        <textarea class="add_product_textarea" name="description" placeholder="Description"></textarea>
        <input class="add_product_input" type="number" name="quantity" placeholder="Quantity" min="0">
        <input class="add_product_input" type="number" step="0.01" name="price" placeholder="Price">
        <input class="add_product_input" type="text" name="manufacturer" placeholder="Manufacturer">
        <input class="add_product_input" type="date" name="expiry_date">
        </div>
        <div class="right_side">
            
        <label>Upload Images (max 5)</label>
        <input type="file" name="images[]" accept="image/*" class="size" multiple>
        
        <button class="add_product_button" type="submit">Add Product</button>
        </div>
    </form>
</div>

<!-- <script src="../../../../public/assets/js/main.js"></script> -->

<script>
const addForm = document.getElementById('addForm');

addForm.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(addForm);

    fetch('product_ajax.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        console.log(data); // Debugging
        alert(data.msg);
        if (data.status == 1) {
            addForm.reset();
        }
    })
    .catch(err => {
        console.error("Fetch error:", err);
        alert("Something went wrong!");
    });
});
</script>

</body>
</html>