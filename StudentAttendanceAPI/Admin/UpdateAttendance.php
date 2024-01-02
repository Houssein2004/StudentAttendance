<?php
require_once("../Config.php");

$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

$students = $data['students'];
$courseId = $students[0]['courseId']; // Assuming courseId is the same for all students

try {
    foreach ($students as $student) {
        $userId = $student['Id'];
        $isAbsent = $student['isAbsent'] ? 0 : 1;

        // Assuming attendance table has columns UserId, CourseId, and IsAbsent
        $insertQuery = "INSERT INTO attendance (UserId, CourseId, IsAbsent,Date) VALUES ('$userId', '$courseId', '$isAbsent',NOW())";

        if (!mysqli_query($con, $insertQuery)) {
            throw new Exception(mysqli_error($con));
        }
    }

    echo "Attendance records inserted successfully";
} catch (Exception $e) {
    http_response_code(500);
    echo "Error: " . $e->getMessage();
} finally {
    if ($con) {
        mysqli_close($con);
    }
}
