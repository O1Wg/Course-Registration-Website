<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id   = (int)$_SESSION["user_id"];
$course_id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$msg       = "";
$error     = false;

if ($course_id <= 0) {
    echo "Invalid course.";
    exit();
}

// get the course details (prepared statement)
$stmt = mysqli_prepare($conn, "SELECT id, title, description, teacher FROM courses WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $course_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$course = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$course) {
    echo "Course not found.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // check if already enrolled
    $check = mysqli_prepare($conn, "SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?");
    mysqli_stmt_bind_param($check, "ii", $user_id, $course_id);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);

    if (mysqli_stmt_num_rows($check) > 0) {
        $msg   = "You are already enrolled in this course.";
        $error = true;
    } else {
        $today = date("Y-m-d");
        $ins   = mysqli_prepare($conn, "INSERT INTO enrollments (user_id, course_id, date_enrolled) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($ins, "iis", $user_id, $course_id, $today);
        if (mysqli_stmt_execute($ins)) {
            $msg = "You have been enrolled successfully.";
        } else {
            $msg   = "Something went wrong.";
            $error = true;
        }
        mysqli_stmt_close($ins);
    }
    mysqli_stmt_close($check);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Enroll</title>
    <link rel="stylesheet" href="style.css">
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
    <h2>Enroll in Course</h2>

    <?php if ($msg != ""): ?>
        <div class="msg <?php echo $error ? 'error' : ''; ?>">
            <?php echo htmlspecialchars($msg); ?>
        </div>
    <?php endif; ?>

    <div class="course-card">
        <h3><?php echo htmlspecialchars($course["title"]); ?></h3>
        <p><?php echo htmlspecialchars($course["description"]); ?></p>
        <p>Teacher: <?php echo htmlspecialchars($course["teacher"]); ?></p>
    </div>

    <!-- only show the button if not yet enrolled -->
    <?php if ($msg == "" || $error): ?>
        <form method="POST">
            <input type="submit" value="Confirm Enrollment">
        </form>
    <?php endif; ?>

    <br>
    <p><a href="courses.php">Back to courses</a></p>
</div>

<footer>
    <p>&copy;2026 SEU Course Registration. All right reserved.</p>
</footer>

</body>
</html>