<?php

$db_name = "library";
$name = "localhost";
$uname = "root";
$password = ""; 

$conn = mysqli_connect($name, $uname, $password, $db_name);
if (!$conn) {
    echo "Connection failed!";
}

?>