<?php

$host = "localhost";
$user = "root";        // change to your username
$pass = "";            // change to your password
$db   = "my_courses";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// make sure text is stored/retrieved as utf-8
mysqli_set_charset($conn, "utf8mb4");
?>