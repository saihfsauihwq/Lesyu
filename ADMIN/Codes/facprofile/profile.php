<?php
session_start();
include("getDB.php");
include("Displayinfo.php");

if (!isset($_SESSION["admin_accounts"])) {
    header("Location: Login-Page.php");
    exit();
}

$userId = $_SESSION["admin_accounts"]["USER-ID"];
$adminName = getAdminName($userId);

$userId = $_GET["ID"];

$userInfo= getUserDataById($userId);

if ($userInfo) {
    $userprofileBlob = $userInfo["Profile"];
  }

$DbHost = "localhost";
$DbUsername = "root";
$DbPassword = "";
$DbName = "researchfiledb";

$fileDb = mysqli_connect($DbHost, $DbUsername, $DbPassword, $DbName);

if (!$fileDb) {
    die("Connection failed: " . mysqli_connect_error());
}

$latestVolumeQuery = "SELECT MAX(YEAR(Date)) AS LatestVolume FROM research_info";
$latestVolumeResult = mysqli_query($fileDb, $latestVolumeQuery);

if ($latestVolumeResult) {
    $latestVolumeRow = mysqli_fetch_assoc($latestVolumeResult);
    $latestVolume = $latestVolumeRow['LatestVolume'];
    $selectedVolume = $latestVolume;
} else {
    // Handle the error if needed
    $selectedVolume = null;
}

mysqli_close($fileDb);
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
    <link rel="Stylesheet" href="Searchbar.css">
    <link rel="Stylesheet" href="nav.css">
    <link rel="Stylesheet" href="Page.css">
    <link rel="Icon" href="/WRRL/IMAGES/favicon.png">
</head>
<body>

<header>
    <div class="Header-background">
        <div class="Logo">
            <a href="/WRRL/ADMIN/Codes/HOME-PAGE/HOME-PAGE.php">
                <img class="Logo-icon" src="/WRRL/IMAGES/mainlogo.png">
            </a>
        </div>
        <div class="Title">
            <p class="Name">Liceo U Repository</p>
            <p class="line">Committed to Total Human Formation!</p>
        </div>
</header>

<nav>
    <div class="Navigation-bar-container">
        <div class="profile">
            <div class="Profile-picture">
                <img class="Ppic" src="Images/gojo.jpg">
                <a class="Username"> <?php echo $adminName; ?> </a>
            </div>
            <div class="nav-buttons">
                <div><img src="/WRRL/IMAGES/house-window.png"><a class="home" href="http://localhost/WRRL/ADMIN/Codes/HOME-PAGE/HOME-PAGE.php">Home</a></div>
                <div><img src="/WRRL/IMAGES/books.png" ><a class="publish" href="/WRRL/ADMIN/Codes/Volume/volume.php?volume=<?php echo $selectedVolume; ?>">Volumes</a></div>
                <div><img src="/WRRL/IMAGES/file-invoice.png    "><a class="publish" href="http://localhost/WRRL/ADMIN/Codes/PUBLISHLIST/Publishlist.php">Uploads</a></div>
                <div><img src="/WRRL/IMAGES/file-upload.png"><a class="publish" href="/WRRL/ADMIN/Codes/PUBLISH-PAGE/PUBLISH-PAGE.php">Upload new paper</a></div>
                <div><img src="/WRRL/IMAGES/user.png"><a style="color: rgb(164,0,1); text-decoration: underline;" class="publish" href="/WRRL/ADMIN/Codes/USERLIST/USERLIST.php">List of Accounts</a></div>
                <div><img src="/WRRL/IMAGES/folders.png"><a class="publish" href="/WRRL/ADMIN/Codes/ARCHIVE-PAGE/ARCHIVE-PAGE.php">Archives</a></div>
                <div class="logoutbtn"><img  src="/WRRL/IMAGES/log-out.png"><a class="logout" href="http://localhost/WRRL/ADMIN/Codes/LOGIN-PAGE/Login-Page.php">Logout</a></div>
            </div>
        </div>
    </div>
</nav>

<main>
<div class="background">
      <div class="Profile-Page">
        <h1>FACULTY PROFILE</h1>
        <a class="edit" href="/WRRL/ADMIN/CODES/facupdateinfo/UpdateProfile.php?ID=<?php echo $userInfo['ID_number']; ?>">Edit</a>
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
</main>
</body>
</html>
