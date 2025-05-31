<?php
include 'connect.php';

if (isset($_POST['signUp'])) {
    $companyName = $_POST['companyName'];
    $email = trim(strtolower($_POST['email']));
    $password = trim($_POST['password']);
    $password = md5($password); 

    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($checkEmail);
    if ($result->num_rows > 0) {
        echo "Email Address Already Exists!";
    } else {
        $insertQuery = "INSERT INTO users(company_name, email, password)
                        VALUES ('$companyName', '$email', '$password')";
        if ($conn->query($insertQuery) === TRUE) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

if (isset($_POST['signIn'])) {
    $email = trim(strtolower($_POST['email']));
    $password = trim($_POST['password']);
    $password = md5($password); 

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        session_start();
        $row = $result->fetch_assoc();

        $_SESSION['email'] = $row['email'];
        $_SESSION['company_name'] = $row['company_name'];
        $_SESSION['user_id'] = $row['id'];
        header("Location: homepage.php");
        exit();
    } else {
        echo "Incorrect Email or Password";
    }
}
?>
