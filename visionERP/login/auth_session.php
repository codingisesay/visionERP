<?php

session_start();
include('loginFunction.php');
$deviceCookie = $_COOKIE['PHPSESSID'];
$userID = $_SESSION['userID'];
include('db.php');
$query = "SELECT * FROM loginLog WHERE deviceCookie = '$deviceCookie' AND userId = '$userID'";
$run = mysqli_query($con,$query);
mysqli_close($con);

    while($data = mysqli_fetch_assoc($run)){

     $activeSession[] = array("Login ID" => $data['loginId'], "Device Name" => $data['deviceName'],
    "Device Cookie" => $data['deviceCookie'],"Device OS" => $data['deviceOS'],"Device Browser" => $data['deviceBrowser'],
    "Date Time Login" => $data['dateTimeLogin'],"Last Activity" => $data['lastActivity'],"Session Status" => $data['sessionStatus'],
    "User ID" => $data['userId']);

    }

    if(!isset($_SESSION['userID']) || $activeSession[0]['Session Status'] == 'Inactive'){

        header('location:index.php');

    }



?>
