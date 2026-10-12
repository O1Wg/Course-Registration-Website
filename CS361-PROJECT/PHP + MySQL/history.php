<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = (int)$_SESSION["user_id"];
$msg     = "";

// cancel (delete) an enrollment if requested
if (isset($_GET["cancel"])) {
    $enroll_id = (int)$_GET["cancel"];
    $del = mysqli_prepare($conn, "DELETE FROM enrollments WHERE id = ? AND user_id = ?");
    mysqli_stmt_bind_param($del, "ii", $enroll_id, $user_id);
    if (mysqli_stmt_execute($del)) {
        $msg = "Enrollment cancelled.";
    }
    mysqli_stmt_close($del);
}

// get all enrollments for this user (prepared statement)
$stmt = mysqli_prepare($conn,
    "SELECT enrollments.id, courses.title, courses.teacher, enrollments.date_enrolled
       FROM enrollments
       JOIN courses ON enrollments.course_id = courses.id
      WHERE enrollments.user_id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Enrollments</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>SEU Course Registration</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="courses.php">Courses</a>
        <a href="profile.php">Edit Profile</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<div class="container">
    <h2>My Enrollments</h2>

    <?php if ($msg != ""): ?>
        <div class="msg"><?php echo htmlspecialchars($msg); ?></div>
    <?php endif; ?>

    <?php if (!$result || mysqli_num_rows($result) == 0): ?>
        <p>You have not enrolled in any courses yet.</p>
        <br>
        <p><a href="courses.php">Browse courses</a></p>
    <?php else: ?>
        <table>
            <tr>
                <th>Course</th>
                <th>Teacher</th>
                <th>Date Enrolled</th>
                <th>Action</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row["title"]); ?></td>
                <td><?php echo htmlspecialchars($row["teacher"]); ?></td>
                <td><?php echo htmlspecialchars($row["date_enrolled"]); ?></td>
                <td>
                    <a href="history.php?cancel=<?php echo (int)$row["id"]; ?>"
                       onclick="return confirm('Are you sure you want to cancel this enrollment?')">
                        Cancel
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>
</div>

<footer>
    <p>&copy;2026 SEU Course Registration. All right reserved.</p>
</footer>

</body>
</html>
