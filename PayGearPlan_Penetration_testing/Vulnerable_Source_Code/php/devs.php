<html lang="en">

<script>
    function validateForm() {
      var form = document.forms["regForm"];
      var username = form.elements["username"].value.trim();
      var password = form.elements["password"].value.trim();
  
      if (username.length == 0 || password.length == 0) {
        alert("Forms are invalid and/or incorrect.");
      }
    }
    </script>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link rel="stylesheet" href="\PayGearPlan\css\login-style.css">
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
            <a href="\PayGearPlan\php\login.php"><div class="fas fa-user" id="login-btn"></div></a>
        </div>
    </header>
   <form action="miresevini.php" method="POST" class="login-form" onsubmit="return validateForm()">
    <h3>login dev mode</h3>
    
    <input type="hidden" name="login_type" value="admin">
    
    <input type="email" name="email" placeholder="your email" class="box" id="email" required>
    <input type="password" name="password" placeholder="your password" class="box" id="password" required>
    
    <input type="submit" value="login now" class="btn">
    </form>
        <script src="script.js"></script>
</body>
</html>