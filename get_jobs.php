<?php
session_start();
include("connect.php");

if (!isset($_SESSION['email'])) {
    echo json_encode([]);
    exit();
}

$email = $_SESSION['email'];

// Get user ID
$user_query = "SELECT id FROM users WHERE email = ?";
$user_stmt = $conn->prepare($user_query);
$user_stmt->bind_param("s", $email);
$user_stmt->execute();
$user_result = $user_stmt->get_result();

if ($user_result->num_rows > 0) {
    $user_id = $user_result->fetch_assoc()['id'];

    $jobs_query = "SELECT id, title FROM jobs WHERE user_id = ?";
    $jobs_stmt = $conn->prepare($jobs_query);
    $jobs_stmt->bind_param("i", $user_id);
    $jobs_stmt->execute();
    $jobs_result = $jobs_stmt->get_result();

    $jobs = [];
    while ($row = $jobs_result->fetch_assoc()) {
        $jobs[] = $row;
    }

    echo json_encode($jobs);
} else {
    echo json_encode([]);
}
?>
