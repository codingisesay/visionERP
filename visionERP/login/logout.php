<?php
    include('db.php');
    session_start();
    $deviceCookie = $_COOKIE['PHPSESSID'];
    $userID = $_SESSION['userID'];
    date_default_timezone_set('Asia/kolkata');
    $time = date("Y-m-d h:i:s");
    $query = "UPDATE loginLog
    SET sessionStatus = 'Inactive' , lastActivity = '$time'
    WHERE deviceCookie = '$deviceCookie' AND userId = '$userID'";
    $run = mysqli_query($con,$query);
    mysqli_close($con);
    session_unset();
    header('location:index.php');
?>