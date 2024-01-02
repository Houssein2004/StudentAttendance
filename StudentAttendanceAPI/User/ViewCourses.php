<?php
require_once("../Config.php");
// Retrieve the raw POST data
$jsonData = file_get_contents('php://input');
// Decode the JSON data into a PHP associative array
$data = json_decode($jsonData, true);
// Check if decoding was successful
$key = "";
if ($data !== null) {
    $key = addslashes(strip_tags($data['Key']));

    if ($key != "your_key") {
        http_response_code(403);
        die("access denied");
    }
}

$query = "SELECT * FROM courses";
$result = mysqli_query($con, $query);
if (mysqli_num_rows($result) == 0) {
    echo array(
        'response' => "No Courses Available",
    );
} else {
    $emparray = array();
    while ($row = mysqli_fetch_assoc($result))
        $emparray[] = $row;

    echo (json_encode($emparray));
    // Free result set
    mysqli_free_result($result);
    mysqli_close($con);
}
