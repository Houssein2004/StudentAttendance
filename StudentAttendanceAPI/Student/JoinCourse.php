<?php
require_once("../Config.php");
// Retrieve the raw POST data
$jsonData = file_get_contents('php://input');
// Decode the JSON data into a PHP associative array
$data = json_decode($jsonData, true);
// Check if decoding was successful
$userId = "";
$courseId = "";
$key = "";
if ($data !== null) {
    $userId = addslashes(strip_tags($data['UserId']));
    $courseId = addslashes(strip_tags($data['CourseId']));

    $key = addslashes(strip_tags($data['Key']));

    if ($key != "your_key" or trim($userId) == "") {
        http_response_code(403);
        die("access denied");
    }
} else {
    http_response_code(403);
    die("access denied");
}

$checkquery = "SELECT * FROM usercouse WHERE UserID ='" . $userId . "' AND CourseID = '" . $courseId . "'";
$checkresult = mysqli_query($con, $checkquery);
if (mysqli_num_rows($checkresult) == 0) {
    $checkMaxNb = "SELECT MaxStudents FROM courses WHERE ID = '" . $courseId . "'";
    $checkMaxResult = mysqli_query($con, $checkMaxNb);
    $checkMaxRow = mysqli_fetch_assoc($checkMaxResult);
    $maxStudents = $checkMaxRow['MaxStudents'];

    $checkquery2 = "SELECT * FROM usercouse WHERE  CourseID = '" . $courseId . "'";
    $checkresult2 = mysqli_query($con, $checkquery);
    if (mysqli_num_rows($checkresult2) < $maxStudents) {
        $query = "INSERT INTO usercouse(`UserID`, `CourseID`) VALUES ('" . $userId . "','" . $courseId . "')";
        $result = mysqli_query($con, $query);
        echo  "Joined Course Successfully";
    } else {
        echo  "Course is full";
    }
} else {
    echo "You are already enrolled in this course";
}
