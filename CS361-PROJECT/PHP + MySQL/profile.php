<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = (int)$_SESSION["user_id"];
$msg     = "";
$error   = false;

// helper: load the current user record
function load_user($conn, $user_id) {
    $stmt = mysqli_prepare($conn, "SELECT id, name, email FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $res  = mysqli_stmt_get_result($stmt);
    $row  = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    return $row;
}

// handle delete account FIRST (so we don't read after destroying)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    // remove their enrollments first
    $d1 = mysqli_prepare($conn, "DELETE FROM enrollments WHERE user_id = ?");
    mysqli_stmt_bind_param($d1, "i", $user_id);
    mysqli_stmt_execute($d1);
    mysqli_stmt_close($d1);
    // then remove the user
    $d2 = mysqli_prepare($conn, "DELETE FROM users WHERE id = ?");
    mysqli_stmt_bind_param($d2, "i", $user_id);
    mysqli_stmt_execute($d2);
    mysqli_stmt_close($d2);

    session_destroy();
    header("Location: index.php");
    exit();
}

// handle save changes (UPDATE)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $new_name  = trim($_POST["name"] ?? "");
    $new_email = trim($_POST["email"] ?? "");

    if ($new_name === "" || $new_email === "") {
        $msg   = "Name and email cannot be empty.";
        $error = true;
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $msg   = "Please enter a valid email address.";
        $error = true;
    } else {
        $upd = mysqli_prepare($conn, "UPDATE users SET name = ?, email = ? WHERE id = ?");
        mysqli_stmt_bind_param($upd, "ssi", $new_name, $new_email, $user_id);
        if (mysqli_stmt_execute($upd)) {
            $_SESSION["user_name"] = $new_name;
            $msg = "Profile updated successfully.";
        } else {
            $msg   = "Could not update profile.";
            $error = true;
        }
        mysqli_stmt_close($upd);
    }
}

$user = load_user($conn, $user_id);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <link rel="stylesheet" href="style.css">
    <script src="validate.js"></script>
</head>
<body>

<header>
    <h1>SEU Course Registration</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="courses.php">Courses</a>
        <a href="history.php">My Enrollments</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<div class="container">
    <h2>Edit Profile</h2>

    <?php if ($msg != ""): ?>
        <div class="msg <?php echo $error ? 'error' : ''; ?>">
            <?php echo htmlspecialchars($msg); ?>
        </div>
    <?php endif; ?>

    <!-- a small bit of info about the account -->
    <h3>Your Account</h3>
    <p style="margin-bottom: 6px;">Account ID: <strong>#<?php echo (int)$user['id']; ?></strong></p>
    <p style="margin-bottom: 6px;">Current name: <strong><?php echo htmlspecialchars($user['name']); ?></strong></p>
    <p style="margin-bottom: 15px;">Current email: <strong><?php echo htmlspecialchars($user['email']); ?></strong></p>

    <p style="margin-bottom: 15px;">
        Update your name or email below and click <em>Save Changes</em>.
        Your password is kept private and cannot be changed from this page.
    </p>

    <!-- update name and email -->
    <form method="POST" action="profile.php" onsubmit="return checkEdit()">
        <label>Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>">

        <label>Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">

        <input type="submit" name="update" value="Save Changes">
    </form>

    <br><br>
    <hr>
    <br>

    <p>Want to delete your account? This will also remove all your enrollments.</p>
    <br>

    <!-- delete account button -->
    <form method="POST" action="profile.php"
          onsubmit="return confirm('Are you sure? This cannot be undone.')">
        <input type="submit" name="delete" value="Delete My Account"
               style="background-color: #c0392b;">
    </form>
</div>

<footer>
    <p>&copy;2026 SEU Course Registration. All right reserved.</p>
</footer>

</body>
</html>
