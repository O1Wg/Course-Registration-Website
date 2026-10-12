<?php
session_start();
include "db.php";

$msg   = "";
$error = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name             = trim($_POST["name"] ?? "");
    $email            = trim($_POST["email"] ?? "");
    $password         = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    // server-side validation
    if ($name === "" || $email === "" || $password === "") {
        $msg   = "Please fill in all fields.";
        $error = true;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg   = "Please enter a valid email address.";
        $error = true;
    } elseif (strlen($password) < 6) {
        $msg   = "Password must be at least 6 characters long.";
        $error = true;
    } elseif ($confirm_password !== "" && $password !== $confirm_password) {
        $msg   = "Passwords do not match.";
        $error = true;
    } else {
        // check if email already taken (prepared statement)
        $check = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            $msg   = "That email is already registered.";
            $error = true;
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt   = mysqli_prepare($conn, "INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hashed);
            if (mysqli_stmt_execute($stmt)) {
                $msg = "Account created. You can now login.";
            } else {
                $msg   = "Something went wrong. Please try again.";
                $error = true;
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_stmt_close($check);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
    <script src="validate.js"></script>
</head>
<body>

<header>
    <h1>SEU Course Registration</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
    </nav>
</header>

<div class="container">
    <h2>Create an Account</h2>

    <?php if ($msg != ""): ?>
        <div class="msg <?php echo $error ? 'error' : ''; ?>">
            <?php echo htmlspecialchars($msg); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="register.php" onsubmit="return checkRegister()">
        <label>Name:</label>
        <input type="text" id="name" name="name">

        <label>Email:</label>
        <input type="email" id="email" name="email">

        <label>Password:</label>
        <input type="password" id="password" name="password">

        <label>Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password">

        <input type="submit" value="Register">
    </form>

    <br>
    <p>Already have an account? <a href="login.php">Login</a></p>
</div>

<footer>
    <p>&copy;2026 SEU Course Registration. All right reserved.</p>
</footer>

</body>
</html>
