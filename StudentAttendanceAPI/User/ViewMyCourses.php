<?php
require_once("../Config.php");

// Retrieve the raw POST data
$jsonData = file_get_contents('php://input');

// Decode the JSON data into a PHP associative array
$data = json_decode($jsonData, true);

// Check if decoding was successful
$userId = "";
$key = "";

if ($data !== null) {
    $userId = addslashes(strip_tags($data['UserId']));
    $key = addslashes(strip_tags($data['Key']));

    if ($key != "your_key" or trim($userId) == "") {
        http_response_code(403);
        die("access denied");
    }
} else {
    http_response_code(403);
    die("access denied");
}

$query = "SELECT * FROM courses WHERE ID IN (SELECT CourseID FROM usercouse WHERE UserID = '" . $userId . "')";
$result = mysqli_query($con, $query);

$emparray = array();

while ($row = mysqli_fetch_assoc($result)) {
    $emparray[] = $row;
}

echo json_encode($emparray);

// Free result set
mysqli_free_result($result);
mysqli_close($con);
