<html lang="en">

<script>
   function validateForm() {
        var form = document.forms["regForm"];
        var username = form.elements["username"].value.trim();
        var password = form.elements["password"].value.trim();
  
        if (username.length == 0 || password.length == 0) {
            alert("Please fill out the forms."); 
            return false; // This stops the form from submitting empty
        } 
        return true; // This ALLOWS the form to go to miresevini.php
    }
    </script>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link rel="stylesheet" href="/PayGearPlan\css\login-style.css">
</head>
<body>
  <header class="header">

        <a href="#" class="logo"> <i class=""></i> PayGearPlan </a>
    
        <nav class="navbar">
            <a href="\PayGearPlan\html\index.html">home</a>
            <a href="\PayGearPlan\html\index.html">features</a>
             <a href="\PayGearPlan\html\index.html">categories</a>           
        </nav>
    
        <div class="icons">
            <a href="\PayGearPlan\php\devs.php"><div class="fas fa-laptop-code" id="menu-btn"></div></a>
            <a href="\PayGearPlan\php\produktet.php"><div class="fas fa-shopping-cart" id="cart-btn"></div></a>
        </div>
    </header>

    <img src="C:\Users\Admin\OneDrive\Desktop\WEB\Projekti Test\assets\img\8B0000.jpg" alt="" srcset="">

   <form name="regForm" action="miresevini.php" method="POST" class="login-form" onsubmit="return validateForm()" autocomplete="on">
    <h3>login now</h3>
    
    <input type="text" name="username" placeholder="your username" class="box" id="username" required>
    
    <input type="password" name="password" placeholder="your password" class="box" id="password" required>
    
    <p>don't have an account? <a href="signup.php">create now</a></p>
    
    <input type="submit" value="login now" class="btn">
</form>

        <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>

        <script src="script.js"></script>
</body>
</html>