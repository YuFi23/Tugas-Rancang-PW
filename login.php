<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($email == "example@example.com" && $password == "password123") {
        $_SESSION['user_email'] = $email;
        $_SESSION['user_authenticated'] = true;

        header("Location: protected_page.php");
        exit;
    } else {
        $login_error = "Email atau kata sandi tidak valid.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($password == $confirm_password) {
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_authenticated'] = true;

        header("Location: protected_page.php");
        exit;
    } else {
        $signup_error = "Kata sandi tidak cocok.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Encore Shield</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('Tugas-Rancang-PW-main\img\background home.png');
            background-size: cover;
            background-position: center;
        }
        .login-container, .signup-container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="login-container">
            <h2 class="text-center mb-4">Welcome To Encore Shield</h2>
            <?php if (isset($login_error)) { ?>
                <div class="alert alert-danger"><?php echo $login_error; ?></div>
            <?php } ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block" name="login">Login</button>
                <div class="text-center mt-3">
                    <a href="#" onclick="toggleForms()">Don't have an account? Sign up now</a>
                </div>
            </form>
        </div>

        <div class="signup-container d-none">
            <h2 class="text-center mb-4">Welcome To Encore Shield</h2>
            <?php if (isset($signup_error)) { ?>
                <div class="alert alert-danger"><?php echo $signup_error; ?></div>
            <?php } ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block" name="signup">Sign Up</button>
                <div class="text-center mt-3">
                    <a href="#" onclick="toggleForms()">Already have an account? Sign in now</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleForms() {
            var loginContainer = document.querySelector('.login-container');
            var signupContainer = document.querySelector('.signup-container');

            loginContainer.classList.toggle('d-none');
            signupContainer.classList.toggle('d-none');
        }
    </script>
</body>
</html>
