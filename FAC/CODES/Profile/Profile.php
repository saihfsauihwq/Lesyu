<?php
session_start();

include("Displayinfo.php");

if (!isset($_SESSION["fac_accounts"])) {
    header("Location: http://localhost/WRRL/FAC/Codes/LOGIN-PAGE/LOGIN-PAGE.php");
    exit();
}

$ID_number = $_SESSION["fac_accounts"]["ID_number"];
$userInfo = getUserInfo($ID_number); 

if ($userInfo) {
  $userprofileBlob = $userInfo["Profile"];
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
    <link rel="Icon" href="/WRRL/IMAGES/favicon.png">

</head>
<body>

<header>
        <div class="Header-background">
            <div class="Logo">
                <a href="/WRRL/FAC/Codes/HOME-PAGE/HOME-PAGE.php">
                    <img class="Logo-icon" src="/WRRL/IMAGES/mainlogo.png">
                </a>
            </div>
            <div class="Title">
                <p class="Name">Liceo U Repository</p>
                <p class="line">Committed to Total Human Formation!</p>
            </div>
    </header>

<nav>
  <div class="Nav-Section">
    <div class="user-ID">
      <p class="label"><?php echo $userInfo['Firstname']; ?><p>
      <p class="IDnum"><?php echo $userInfo['ID_number']; ?><p>
    </div>
    <ul>
      <li style="background-color: rgba(148, 147, 144, 0.153);" ><a class="nav_bar" href="http://localhost/WRRL/FAC/Codes/Profile/Profile.php">Profile</a></li>
      <li><a class="nav_bar" href="http://localhost/WRRL/FAC/Codes/My_uploads/myupload.php">My uploads</a></li>
      <li><a class="nav_bar" href="http://localhost/WRRL/FAC/Codes/Publish/Publish.php">Upload new paper</a></li>
      <li><a class="nav_bar" href="http://localhost/WRRL/FAC/Codes/Update/Update.php">Update Account</a></li>
      <li><a class="nav_bar" href="http://localhost/WRRL/FAC/Codes/LOGIN-PAGE/LOGIN-PAGE.php">Logout</a></li>
    </ul>
  </div>
</nav>

 <main>

  <div class="background">
      <div class="Profile-Page">
        <h2>Personal Information</h2>
        <a class="edit" href="/WRRL/FAC/CODES/Updateinfo/UpdateProfile.php">Edit</a>
        <img class="profimg" src="data:image/jpeg;base64,<?php echo base64_encode($userprofileBlob); ?>">
        <div class="ID-number"><label>ID-number</label><span><?php echo $userInfo['ID_number']; ?></span></div>
        <div class="Profile-Info">
          <div class="Info-Column">
            <div class="Info-Item"><label>Name:</label><span><?php echo $userInfo['Firstname']; ?> <?php echo $userInfo['Lastname']; ?></span></div>
            <div class="Info-Item"><label>Gender:</label><span><?php echo $userInfo['Gender']; ?></span></div>
            <div class="Info-Item"><label>Email:</label><span><?php echo $userInfo['Liceo_Email']; ?></span></div>
          </div>
          <div class="Info-Column">
            <div class="Info-Item"><label>Strand:</label><span><?php echo $userInfo['Strand']; ?></span></div>
            <div class="Info-Item"><label>Status:</label><span><?php echo $userInfo['Status']; ?></span></div>
            <div class="Info-Item"><label>Backup Email:</label><span><?php echo $userInfo['Backup_email']; ?></span></div>
          </div>
        </div>
      </div>
    </div>
  </div>
 </main>

</body>
</html>
