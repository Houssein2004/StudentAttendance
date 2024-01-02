<?php
require_once("../Config.php");

// Retrieve the raw POST data
$jsonData = file_get_contents('php://input');

// Decode the JSON data into a PHP associative array
$data = json_decode($jsonData, true);
$userId = "";

// Check if decoding was successful
$key = "";
if ($data !== null) {
    $key = addslashes(strip_tags($data['Key']));
    $userId = addslashes(strip_tags($data['UserId']));

    if ($key != "your_key" or trim($userId) == "") {
        http_response_code(403);
        die("access denied");
    }
}

// Query to get courses with absent count
$query = "SELECT
            courses.*,
            COUNT(attendance.UserId) AS AbsentCount
          FROM
            courses
          LEFT JOIN
            usercouse ON courses.ID = usercouse.CourseID
          LEFT JOIN
            attendance ON courses.ID = attendance.CourseID AND usercouse.UserID = attendance.UserID AND attendance.isAbsent = 1
          WHERE
            usercouse.UserID = '$userId'
          GROUP BY
            courses.ID";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) == 0) {
    echo json_encode(array('response' => "No Courses Available"));
} else {
    $emparray = array();
    while ($row = mysqli_fetch_assoc($result))
        $emparray[] = $row;

    echo json_encode($emparray);

    // Free result set
    mysqli_free_result($result);
    mysqli_close($con);
}
