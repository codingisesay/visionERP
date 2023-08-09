<html>
    <head>
        <title></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

        <link rel="stylesheet" href="css/loadLoginForm.css">
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
  <?php
  include('config.php');
  include('db.php');
  include('loginFunction.php');
  include('library/UserInformation.php');
  session_start();
  if(isset($_POST['submit']) && !empty($_POST['username']) && !empty($_POST['password'])){
    $userName = mysqli_real_escape_string($con,$_POST['username']);
    $passWord = mysqli_real_escape_string($con,$_POST['password']);
    validateAndCreateSession($userName,$passWord);

  }else{
    $deviceCookie = $_COOKIE['PHPSESSID'];
$userID = $_SESSION['userID'];
$query = "SELECT * FROM loginLog WHERE deviceCookie = '$deviceCookie' AND userId = '$userID'";
$run = mysqli_query($con,$query);
mysqli_close($con);

    while($data = mysqli_fetch_assoc($run)){

     $activeSession[] = array("Login ID" => $data['loginId'], "Device Name" => $data['deviceName'],
    "Device Cookie" => $data['deviceCookie'],"Device OS" => $data['deviceOS'],"Device Browser" => $data['deviceBrowser'],
    "Date Time Login" => $data['dateTimeLogin'],"Last Activity" => $data['lastActivity'],"Session Status" => $data['sessionStatus'],
    "User ID" => $data['userId']);

    } 
 if(isset($_SESSION['userID']) && $activeSession[0]['Session Status'] == 'Active'){

        header('location:dashboard.php');

    }elseif(isset($_SESSION['userID']) || $activeSession[0]['Session Status'] == 'Inactive'){

        loadLoginForm();

    }else{
        loadLoginForm();
    }
    
  }
            
      ?> 
 

    </body>
</html>
