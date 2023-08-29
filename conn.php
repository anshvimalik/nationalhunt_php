<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "salon";
$conn = mysqli_connect($servername, $username, $password, $database);
if($conn->connect_error){
   echo "connection failed";
}else{}

?>