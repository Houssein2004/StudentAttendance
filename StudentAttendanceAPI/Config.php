<?php
define("SERVER_NAME", "localhost");
define("USERNAME", "root");
define("PASSWORD", "");
define("DATABASE", "studentattendance");

$con = mysqli_connect(SERVER_NAME, USERNAME, PASSWORD, DATABASE);

if (!$con) {
    die("Error: " . mysqli_connect_error());
    // header("Location: error.php");
}
// <?php
// // header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
// // header("Cache-Control: post-check=0, pre-check=0", false);
// // header("Pragma: no-cache");

// $con = mysqli_connect("localhost", "userId", "userPass", "databaseId");
// // Check connection
// if (mysqli_connect_errno()) {
//     echo "Failed to connect to MySQL: " . mysqli_connect_error();
// }