<?php
require_once("../Config.php");
// Retrieve the raw POST data
$jsonData = file_get_contents('php://input');
// Decode the JSON data into a PHP associative array
$data = json_decode($jsonData, true);
// Check if decoding was successful
$email = "";
$password = "";
$key = "";
if ($data !== null) {
    $email = addslashes(strip_tags($data['Email']));
    $password = addslashes(strip_tags($data['Password']));
    $key = addslashes(strip_tags($data['Key']));

    if ($key != "your_key" or trim($email) == "") {
        http_response_code(403);
        die("access denied");
    }
}

$query = "SELECT * FROM user WHERE Email='" . $email . "'";
$result = mysqli_query($con, $query);
if (mysqli_num_rows($result) == 0) {
    http_response_code(403);
    die("Invalid Email");
} else {
    $row = mysqli_fetch_array($result);
    if ($password == $row["Password"]) {
        $response = array(
            'response' => "Ok",
            'userID' => $row['ID'],
            'Role' => $row['Role']
        );
        $jsonData = json_encode($response);
        echo $jsonData;
    } else {
        http_response_code(403);
        die("Invalid Password");
    }
}
