<?php
include('database.php');
session_start();

$result = $conn->query("SELECT * FROM konser");
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
            <li><a href="about.php">ABOUT US</a></li>
            <li><a href="contact.php">CONTACT US</a></li>
            <?php if (isset($_SESSION['email'])): ?>
            <li class="user-avatarr">
                <img src="img/<?php echo isset($_SESSION['avatar']) ? $_SESSION['avatar'] : 'avatar.png'; ?>" alt="Avatar" class="avatarr" id="avatar">
                <div class="dropdown-menu" id="dropdown-menu">
                    <p class="user-name"><?php echo $_SESSION['username']; ?></p>
                    <a href="logout.php" class="logout">Logout</a>
                </div>
            </li>
            <?php else: ?>
            <li><a href="login.php">LOGIN</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="container">
        <div class="concert-grid">
        <?php while ($row = $result->fetch_assoc()): ?>
    <div class="concert-card">
        <img src="img/<?php echo $row['gambar']; ?>" alt="<?php echo $row['nama_artis']; ?> Concert" />
        <h3><?php echo $row['nama_artis']; ?> - <?php echo date('d F Y', strtotime($row['tanggal'])); ?><br><?php echo $row['tempat']; ?></h3>
        <a href="pesen.php?concert=<?php echo $row['id']; ?>">
            <button>
                <span>BUY TICKET</span>
            </button>
        </a>
    </div>
    <?php endwhile; ?>
        </div>
    </div>
</body>
<script src="about.js"></script>
</html>
