<?php
session_start();
include("connect.php");

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    echo "unauthorized";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["job-title"];
    $description = $_POST["job-description"];
    $requirements = $_POST["job-requirements"];
    $user_email = $_SESSION['email'];

    // Get user ID from email
    $user_query = "SELECT id FROM users WHERE email = ?";
    $user_stmt = $conn->prepare($user_query);
    $user_stmt->bind_param("s", $user_email);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();
    
    if ($user_result->num_rows > 0) {
        $user_row = $user_result->fetch_assoc();
        $user_id = $user_row['id'];
        
        // Insert job with user_id
        $sql = "INSERT INTO jobs (title, description, requirements, user_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $title, $description, $requirements, $user_id);

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "error";
        }
        $stmt->close();
    } else {
        echo "user_not_found";
    }
    
    $user_stmt->close();
    $conn->close();
}
?>