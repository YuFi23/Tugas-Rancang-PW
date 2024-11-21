<?php
$host = 'localhost'; 
$username = 'root';
$password = '';
$dbname = 'dbtiket';

$conn = mysqli_connect($host, $username, $password, $dbname);
if($conn->connect_errno){
    echo 'failed to connect to MySQL: ' .mysqli_connect_error();
    exit();
}
?>
