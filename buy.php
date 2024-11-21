<?php
session_start();

// Jika belum login, arahkan ke halaman login
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit(); // Pastikan tidak ada proses lebih lanjut setelah redirect
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar Example</title>
    <link rel="stylesheet" href="buy.css">
</head>
<body>
    <nav class="navbar">
        <div class="merk">EncS</div>
        <input type="checkbox" id="menu-toggle" class="menu-toggle">
        <label for="menu-toggle" class="menu-icon">&#9776;</label> 
        <ul class="nav-links">
            <li><a href="home.php">HOME</a></li>
            <li><a href="buy.php">BUY TICKET</a></li>
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
        </ul>
    </nav>
    <div class="container">
        <div class="concert-grid">
            <div class="concert-card">
                <img src="img/selena.png" alt="Selena Gomez Concert">
                <h3>Selena Gomez - 03 October 2024</h3>
                <a href="pesen.html?concert=selena">
                    <button>
                        <span>BUY TICKET</span>
                    </button>
                </a>               
            </div>

            <div class="concert-card">
                <img src="img/taylor.png" alt="Taylor Swift Concert">
                <h3>Taylor Swift - 11 December 2024</h3>
                <a href="pesen.html?concert=taylor">
                    <button>
                        <span>BUY TICKET</span>
                    </button>
                </a> 
            </div>
            
            <div class="concert-card">
                <img src="img/black pink.png" alt="Blackpink Concert">
                <h3>Black Pink - 04 September 2024</h3>
                <a href="pesen.html?concert=blackpink">
                    <button>
                        <span>BUY TICKET</span>
                    </button>
                </a>
            </div>

            <div class="concert-card">
                <img src="img/wonder.png" alt="Shawn Mendes Concert">
                <h3>Shawn Mendes - 23 May 2024</h3>
                <a href="pesen.html?concert=shawn">
                    <button>
                        <span>BUY TICKET</span>
                    </button>
                </a>
            </div>

            <div class="concert-card">
                <img src="img/havana.png" alt="Camila Cabello Concert">
                <h3>Camila Cabello - 20 May 2024</h3>
                <a href="pesen.html?concert=camila">
                    <button>
                        <span>BUY TICKET</span>
                    </button>
                </a>
            </div>

            <div class="concert-card">
                <img src="img/lauv.png" alt="Lauv Concert">
                <h3>Lauv - 15 January 2024</h3>
                <a href="pesen.html?concert=lauv">
                    <button>
                        <span>BUY TICKET</span>
                    </button>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
