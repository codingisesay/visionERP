<?php
function loadLoginForm(){
  $output = '<div class="container">
    <div class="login-container">
      <h2 class="text-center">Login</h2>
      <form id="loginForm" action="index.php" method="POST">
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" placeholder="Enter your username" required>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Enter your password" required>
        </div>
        <button type="submit" class="btn btn-login" name="submit">Login</button>
      </form>
    </div>
  </div>';
  echo $output;
}

function validateAndCreateSession($userName,$passWord){
  include('db.php');
  $userVerified = userAuthentication($userName,$passWord);
  if($userVerified == 1){

    $userDataFromUserTable = getUserData($userName); // returning array
    $userAllowedSesion = $userDataFromUserTable['allowedSessions']; //Obtaining allowed session of user
    $userActiveSession = userActiveSessions($userDataFromUserTable['userID']); //Obtaining user active session
    $oldOrNewUser = oldOrNewUser($userDataFromUserTable['userID']);//identify user us old or new

    $userID = $userDataFromUserTable['userID'];
    $userPermission = $userDataFromUserTable['userPermission'];
    $deviceName = UserInfo::get_device();
    $deviceCookie = $_COOKIE['PHPSESSID'];
    $deviceOs =  UserInfo::get_os();
    $deviceBrowser = UserInfo::get_browser();

    if($userAllowedSesion > $userActiveSession){
      if($oldOrNewUser == 0){ // means no any entry for this user in loginlog table

        createSessionAndInsertLoginLog($userID,$userPermission,$deviceName,$deviceCookie,$deviceOs,$deviceBrowser);

         }elseif($oldOrNewUser > 0){

          $sessionStatus = checkSessionStatusWithSessionCookie($deviceCookie,$userID);

        if($sessionStatus == 1){

           updateSession($deviceCookie,$userID,$userPermission);

        }else{

           createSessionAndInsertLoginLog($userID,$userPermission,$deviceName,$deviceCookie,$deviceOs,$deviceBrowser);

         }

      }
     
    }elseif($userAllowedSesion <= $userActiveSession){

      $sessionStatus = checkSessionStatusWithSessionCookie($deviceCookie,$userID);
      if($sessionStatus == 1){
        deactiveTheOldestSession($userID);
        updateSession($deviceCookie,$userID,$userPermission);

      }else{
        $runForDeavtive = deactiveTheOldestSession($userID);
        if($runForDeavtive){
          createSessionAndInsertLoginLog($userID,$userPermission,$deviceName,$deviceCookie,$deviceOs,$deviceBrowser);
        }
        
      }

    }

  }elseif($userVerified == 0){
    echo "User Not Varified";
  }

}
// User Authentication
function userAuthentication($userName,$passWord){

if(validateThroughDB($userName, $passWord) == 1){

  return 1;

}elseif(validateThroughLDAP($userName, $passWord) == 1){

  return 1;

}else{

  return 0;

}
}
// Validate through LDAP
function validateThroughLDAP($userName, $passWord){
   return 0;
}
//Validate through Database
function validateThroughDB($userName, $passWord){
  include('db.php');
  $query    = "SELECT * FROM `users` WHERE userMailId='$userName'
               AND userPassword='" . md5($passWord) . "'";
  $result = mysqli_query($con, $query);
  $rows = mysqli_num_rows($result);
  mysqli_close($con);
  if($rows == 1){
      return 1;
  }
  else{
      return 0;
  }
}
//Get User Information
function getUserData($userName){
  include('db.php');
  $query = "SELECT * FROM users WHERE userMailId = '$userName'";
  $run = mysqli_query($con,$query);
  mysqli_close($con);
  return $userDataFromUserTable = mysqli_fetch_assoc($run);

}

//User Active Sessions
function userActiveSessions($userID){
  include('db.php');
  $query = "SELECT * FROM loginLog WHERE sessionStatus = 'Active' AND userId = '$userID'";
  $run = mysqli_query($con,$query);
  return mysqli_num_rows($run);
  mysqli_close($con);
}

//old or new user
function oldOrNewUser($userID){
  include('db.php');
  $query = "SELECT * FROM loginLog WHERE userId = '$userID'";
  $run = mysqli_query($con,$query);
  return mysqli_num_rows($run);

}
//Create And Insert session
function createSessionAndInsertLoginLog($userID,$userPermission,$deviceName,$deviceCookie,$deviceOs,$deviceBrowser){
include('db.php');
$_SESSION['userID'] = $userID;
$_SESSION['userPermission'] = $userPermission;

$query="INSERT INTO `loginLog` (`deviceName`, `deviceCookie`, `deviceOS`, `deviceBrowser`, `dateTimeLogin`, `lastActivity`, `sessionStatus`, `userId`)
 VALUES 
 ('$deviceName', '$deviceCookie', '$deviceOs', '$deviceBrowser', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'Active', '$userID')";
$result = mysqli_query($con,$query);
mysqli_close($con);
if($result){
  header('location:dashboard.php');
}
}

//Check session status with session cookie and userid
function checkSessionStatusWithSessionCookie($deviceCookie,$userID){
  include('db.php');
  $query = "SELECT * FROM loginLog WHERE deviceCookie = '$deviceCookie' AND userId = '$userID'";
  $run = mysqli_query($con,$query);
  mysqli_close($con);
  return mysqli_num_rows($run);
  

}
//update session in db and also set session variable agin
function updateSession($deviceCookie,$userID,$userPermission){
  include('db.php');
  session_start();
  $_SESSION['userID'] = $userID;
  $_SESSION['userPermission'] = $userPermission;

$query = "UPDATE loginLog
SET sessionStatus = 'Active'
WHERE deviceCookie = '$deviceCookie' AND userId = '$userID'";
$run = mysqli_query($con,$query);
mysqli_close($con);
if($run){

  header('location:dashboard.php');

}
}

// Deactive the Oldest session of user
function deactiveTheOldestSession($userID){
  include('db.php');
  $query = "SELECT * FROM loginLog WHERE sessionStatus = 'Active' AND userId = '$userID'";
  $run = mysqli_query($con,$query);
  while($data = mysqli_fetch_assoc($run)){

    $activeSession[] = array("Login ID" => $data['loginId'], "Device Name" => $data['deviceName'],
    "Device Cookie" => $data['deviceCookie'],"Device OS" => $data['deviceOS'],"Device Browser" => $data['deviceBrowser'],
    "Date Time Login" => $data['dateTimeLogin'],"Last Activity" => $data['lastActivity'],"Session Status" => $data['sessionStatus'],
    "User ID" => $data['userId']);

  }

$minSessionTime =  min($activeSession[0]['Date Time Login'],$activeSession[1]['Date Time Login']);
$queryForDeavtive = "UPDATE loginLog
SET sessionStatus = 'Inactive'
WHERE dateTimeLogin = '$minSessionTime' AND userId = '$userID'";
return $runForDeavtive = mysqli_query($con,$queryForDeavtive);

}


?>