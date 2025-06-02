<?php
session_start();
include("connect.php");

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    echo "unauthorized";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $job_id = $_POST['id'];
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
        
        // Delete job only if it belongs to the current user
        $sql = "DELETE FROM jobs WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $job_id, $user_id);
        
        if ($stmt->execute() && $stmt->affected_rows > 0) {
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