<?php

$host="localhost";
$username="root";
$pass="";
$db="hireswift_c";
$conn=new mysqli($host,$username,$pass,$db);
if($conn->connect_error){
    echo "Connection failed: ".$conn->connect_error;
}

?>