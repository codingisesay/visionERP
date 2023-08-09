<?php
    include("config.php");
    // Assuming you have a database connection already established
    $con = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
    if (mysqli_connect_errno()){
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
?>
