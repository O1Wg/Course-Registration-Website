<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>SEU Course Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>SEU Course Registration</h1>
    <nav>
        <?php if (isset($_SESSION["user_id"])): ?>
            <a href="courses.php">Courses</a>
            <a href="history.php">My Enrollments</a>
            <a href="profile.php">Edit Profile</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>
</header>

<div class="container">
    <h2>Welcome</h2>
    <p>This site lets SEU students register an account and sign up for online courses
       offered by the College of Computing and Informatics.</p>
    <br>

    <h3>What you can do here</h3>
    <ul style="margin-left: 20px; margin-bottom: 15px;">
        <li>Create an account with your name and email.</li>
        <li>Browse the list of available courses and their teachers.</li>
        <li>Enroll in any course with a single click.</li>
        <li>View or cancel the courses you have enrolled in.</li>
        <li>Update your profile information at any time.</li>
    </ul>
    <br>

    <?php if (isset($_SESSION["user_id"])): ?>
        <p>You are logged in as <strong><?php echo htmlspecialchars($_SESSION["user_name"]); ?></strong>.</p>
        <br>
        <p><a href="courses.php">Browse available courses</a></p>
        <br>
        <p><a href="history.php">View your enrolled courses</a></p>
    <?php else: ?>
        <p>New here? <a href="register.php">Create an account</a> to get started.</p>
        <br>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    <?php endif; ?>
</div>

<footer>
    <p>&copy;2026 SEU Course Registration. All right reserved.</p>
</footer>

</body>
</html>