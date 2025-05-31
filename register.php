<?php
include 'connect.php';

if(isset($_POST['signUp'])){
    $companyName = $_POST['companyName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = password_hash($password, PASSWORD_DEFAULT); // Hash the password

    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($checkEmail);
    if($result->num_rows > 0){
        echo "Email Address Already Exists!";
    }
    else{
        $insertQuery = "INSERT INTO users(company_name, email, password)
                       VALUES ('$companyName', '$email', '$password')";
        if($conn->query($insertQuery) === TRUE){
            header("location: index.php");
        }
        else{
            echo "Error:".$conn->error;
        }
    }
}

if(isset($_POST['signIn'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        session_start();
        $row = $result->fetch_assoc();
        if(password_verify($password, $row['password'])){
            $_SESSION['email'] = $row['email'];
            $_SESSION['company_name'] = $row['company_name'];
            $_SESSION['user_id'] = $row['id'];
            header("location: homepage.php");
            exit();
        }
        else{
            echo "Not Found, Incorrect Email or Password";
        }
    }
    else{
        echo "Not Found, Incorrect Email or Password";
    }
}
?>