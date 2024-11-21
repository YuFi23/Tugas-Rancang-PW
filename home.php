 <?php
session_start();

?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encore Shield</title>
    <link rel="stylesheet" href="home.css">
</head>
<body>
    <nav class="navbar">
        <div class="merk">EncS</div>
        <input type="checkbox" id="menu-toggle" class="menu-toggle">
        <label for="menu-toggle" class="menu-icon">&#9776;</label>
        <ul class="nav-links">
            <li><a href="home.php">HOME</a></li>
            <li><a href="about.php">ABOUT US</a></li>
            <li><a href="contact.php">CONTACT US</a></li>
            <?php if (isset($_SESSION['email'])): ?>
    <!-- If the user is logged in, display their avatar and a logout option -->
    <li class="user-avatarr">
        <img src="img/<?php echo isset($_SESSION['avatar']) ? $_SESSION['avatar'] : 'avatar.png'; ?>" alt="Avatar" class="avatarr">
        <span><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?></span>
    </li>
    <li><a href="logout.php">Logout</a></li>
<?php else: ?>
    <!-- If the user is not logged in, show the login link -->
    <li><a href="login.php">LOGIN</a></li>
<?php endif; ?>
    </nav>

    <h1 class="welcome">WELCOME TO ENCORE SHIELD</h1>
    <img src="img/logo.png" class="logo3" alt="Encore Shield Logo"/>
    <div class="order-container">
        <a class="order" href="buy.php">ORDER NOW!</a>
    </div>

    <div class="container card-container section">
        <div class="card">
            <div class="avatar"><img src="img/yerky.jpg" alt="Avatar"></div>
            <h2>Yerky</h2>
            <p>672023186</p>
        </div>
        <div class="card">
            <div class="avatar"><img src="img/praditya.jpg" alt="Avatar"></div>
            <h2>Praditya</h2>
            <p>672023088</p>
        </div>
        <div class="card">
            <div class="avatar"><img src="img/hana.jpg" alt="Avatar"></div>
            <h2>Hana</h2>
            <p>672023004</p>
        </div>
        <div class="card">
            <div class="avatar"><img src="img/mattia.jpg" alt="Avatar"></div>
            <h2>Mattia</h2>
            <p>672023005</p>
        </div>
    </div>

    <div class="container">
        <section class="about-section section">
            <p>
                Encore Shield (EncS) is a website platform that allows users to buy concert tickets online
                easily, quickly, and safely. Music fans can discover various concerts, get complete information, 
                and make reservations directly from home. Encore Shield aims to be the main solution for music lovers.
            </p>
            <p>
                Additionally, Encore Shield provides responsive customer support services to assist users in the
                purchasing process. The platform ensures optimal access speed and real-time information on ticket availability.
            </p>
        </section>
    </div>
</body>

<script src="home.js"></script>

</html>
