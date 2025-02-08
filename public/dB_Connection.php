<?php
$servername = "localhost";
$username = "root";
$password = "";
$db = "nph_solar";

//create connection
$conn = new mysqli($servername, $username, $password, $db);

//check connection
if($conn ->connect_error){
    die("connection failed : ".$conn->connect_error);
}

//echo "connected successfullly <br>";

?>