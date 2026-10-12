<?php
session_start();
include "db.php";

// send to login if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// get all courses from database
$result = mysqli_query($conn, "SELECT id, title, description, teacher FROM courses");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Courses</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>SEU Course Registration</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="history.php">My Enrollments</a>
        <a href="profile.php">Edit Profile</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<div class="container">
    <h2>Available Courses</h2>

    <?php if (!$result || mysqli_num_rows($result) === 0): ?>
        <p>No courses are available yet.</p>
    <?php else: ?>
        <?php while ($course = mysqli_fetch_assoc($result)): ?>
            <div class="course-card">
                <h3><?php echo htmlspecialchars($course["title"]); ?></h3>
                <p><?php echo htmlspecialchars($course["description"]); ?></p>
                <p>Teacher: <?php echo htmlspecialchars($course["teacher"]); ?></p>
                <a href="enroll.php?id=<?php echo (int)$course["id"]; ?>">Enroll in this course</a>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

<footer>
    <p>&copy;2026 SEU Course Registration. All right reserved.</p>
</footer>

</body>
</html>
