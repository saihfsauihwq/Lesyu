<?php
session_start();

include("Displayinfo.php");
include("Update_process.php");

if (!isset($_SESSION["user_accounts"])) {
    header("Location: http://localhost/WRRL/USER/Codes/LOGIN-PAGE/LOGIN-PAGE.php");
    exit();
}

$ID_number = $_SESSION["user_accounts"]["ID_number"];
$userInfo = getUserInfo($ID_number);

if ($userInfo) {
  $userprofileBlob = $userInfo["Profile"];
}

$passwordUpdated = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the new password from the form
  $newPassword = $_POST['new_password'];
  $confirmPassword = $_POST['confirm_password'];

  if (!empty($newPassword) && $newPassword == $confirmPassword) {
      updatePassword($ID_number, $newPassword);
      $passwordUpdated = true;
  } else {
      $errorMessage = "Password didn't match.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="Author" content="Jhon llyod Navarro">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Research Papers repository For Liceo">
    <meta name="keywords" content="Research, Repository, Liceo, University, Admin interface">
    <title>Web-Based Research Repository</title>
    <link rel="Stylesheet" href="General.css">
    <link rel="Stylesheet" href="Header.css">
    <link rel="Stylesheet" href="main.css">
    <link rel="Stylesheet" href="nav.css">
    <link rel="Stylesheet" href="shake.css">
    <link rel="Icon" href="/WRRL/IMAGES/favicon.png">

    <script>
        // JavaScript function to display an alert on page load
        window.onload = function() {
            <?php
            // Check if password was updated successfully
            if ($passwordUpdated) {
                echo 'alert("Account updated successfully!");';
                echo 'window.location.href = "http://localhost/WRRL/USER/Codes/Profile/Profile.php";';
            }
            ?>
        };
    </script>
</head>
<body>

<header>
        <div class="Header-background">
            <div class="Logo">
                <a href="/WRRL/USER/Codes/HOME-PAGE/HOME-PAGE.php">
                    <img class="Logo-icon" src="/WRRL/IMAGES/mainlogo.png">
                </a>
            </div>
            <div class="Title">
                <p class="Name">Liceo U Repository</p>
                <p class="line">Committed to Total Human Formation!</p>
            </div>
        </div>
    </header>

<nav>
  <div class="Nav-Section">
    <div class="user-ID">
      <p class="label"><?php echo $userInfo['Firstname']; ?><p>
      <p class="IDnum"><?php echo $userInfo['ID_number']; ?><p>
    </div>
    <ul>
    <li><a class="nav_bar" href="http://localhost/WRRL/USER/Codes/Profile/Profile.php">Profile</a></li>
      <li style="background-color: rgba(148, 147, 144, 0.153);"><a class="nav_bar" href="http://localhost/WRRL/USER/Codes/Update/Update.php">Update Account</a></li>
      <li><a class="nav_bar" href="http://localhost/WRRL/USER/Codes/LOGIN-PAGE/LOGIN-PAGE.php">Logout</a></li>
    </ul>
  </div>
</nav>

 <main>

  <div class="background">
    <form action="" method="post">
    <div class="container">
      <h1 class="Header" >Update Account</h1>

      <p class="error"><?php echo isset($errorMessage) ? $errorMessage : ""; ?></p>
      <div class="Con">
        <div class="column1" >
            <div class="infocon" >

              <h3>Name</h3>
              <p> <?php echo $userInfo['Firstname']; ?>  <?php echo $userInfo['Lastname']; ?></p>
            </div>

            <div class="infocon" >
              <h3> New Password</h3>
              <input type="Password" name="new_password" placeholder="-" required>
            </div>


          </div>

          <div class="column2" >

            <div class="infocon">
              <h3 >Email</h3>
              <p> <?php echo $userInfo['Liceo_Email']; ?></p>
            </div>

            <div class="infocon" >
              <h3>Confirm New Password</h3>
              <input type="password" name="confirm_password" placeholder="-" required>
            </div>

          </div>
        </div>
        <input type="submit" value="Save Changes" class="Update">
      </div>
    </div>
    </form>
    
  </div>
 </main>

</body>
</html>