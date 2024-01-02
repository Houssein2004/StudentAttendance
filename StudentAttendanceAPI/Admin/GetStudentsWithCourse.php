<?php
require_once("../Config.php");
// Retrieve the raw POST data
$jsonData = file_get_contents('php://input');
// Decode the JSON data into a PHP associative array
$data = json_decode($jsonData, true);
$courseId = "";
$userId = "";
if ($data !== null) {
    $userId = addslashes(strip_tags($data['UserId']));
    $courseId = addslashes(strip_tags($data['CourseId']));
    $key = addslashes(strip_tags($data['Key']));

    if ($key != "your_key" or trim($userId) == "") {
        http_response_code(403);
        die("access denied");
    }
}
$query = "SELECT * FROM user WHERE ID = '" . $userId . "' AND Role = 'Admin'";

$checkUser = mysqli_query($con, $query);
if (!(mysqli_num_rows($checkUser) != 0)) {
    http_response_code(403);
    // not an admin
    die("Invalid User");
} else {
    $sql = "SELECT u.*, 
    (SELECT COUNT(*) 
     FROM attendance sa 
     WHERE sa.UserID = u.ID AND sa.CourseID = '$courseId' AND sa.isAbsent = '1') AS Absent 
FROM user u 
WHERE u.ID IN (SELECT UserID FROM usercouse WHERE CourseID = '$courseId') 
AND u.Role = 'student'";
    if ($result = mysqli_query($con, $sql)) {
        $emparray = array();
        while ($row = mysqli_fetch_assoc($result))
            $emparray[] = $row;

        echo (json_encode($emparray));
        // Free result set
        mysqli_free_result($result);
        mysqli_close($con);
    }
}
