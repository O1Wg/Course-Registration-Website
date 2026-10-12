<?php
session_start();
include "db.php";

$msg   = "";
$error = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    // look up user by email (prepared statement)
    $stmt = mysqli_prepare($conn, "SELECT id, name, password FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user   = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($user && password_verify($password, $user["password"])) {
        // regenerate session id to prevent fixation
        session_regenerate_id(true);
        $_SESSION["user_id"]   = $user["id"];
        $_SESSION["user_name"] = $user["name"];
        header("Location: courses.php");
        exit();
    } else {
        $msg   = "Wrong email or password.";
        $error = true;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <script src="validate.js"></script>
</head>
<body>

<header>
    <h1>SEU Course Registration</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="register.php">Register</a>
    </nav>
</header>

<div class="container">
    <h2>Login</h2>

    <?php if ($msg != ""): ?>
        <div class="msg <?php echo $error ? 'error' : ''; ?>">
            <?php echo htmlspecialchars($msg); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="login.php" onsubmit="return checkLogin()">
        <label>Email:</label>
        <input type="email" id="email" name="email">

        <label>Password:</label>
        <input type="password" id="password" name="password">

        <input type="submit" value="Login">
    </form>

    <br>
    <p>No account yet? <a href="register.php">Register</a></p>
</div>

<footer>
    <p>&copy;2026 SEU Course Registration. All right reserved.</p>
</footer>

</body>
</html>
