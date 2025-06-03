<?php
include 'db_connection.php'; // Make sure your DB connection is here

$id = $_POST['id'];
$title = $_POST['title'];

if ($id && $title) {
    $stmt = $conn->prepare("UPDATE jobs SET title = ? WHERE id = ?");
    $stmt->bind_param("si", $title, $id);
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
    $stmt->close();
}
?>
