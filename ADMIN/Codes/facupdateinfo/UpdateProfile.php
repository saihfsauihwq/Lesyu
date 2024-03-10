<?php
session_start();
include("getDB.php");
include("Displayinfo.php");
include("Updateinfo.php");

if (!isset($_SESSION["admin_accounts"])) {
    header("Location: Login-Page.php");
    exit();
}

$userId = $_SESSION["admin_accounts"]["USER-ID"];
$adminName = getAdminName($userId);

$userId = $_GET["ID"];

$userInfo = getUserDataById($userId);

if ($userInfo) {
    $userprofileBlob = $userInfo["Profile"];
}


$updateSuccess = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ID_number = $_SESSION["fac_accounts"]["ID_number"];
    $userInfo = getUserDataById($ID_number);


  $updatedValues = array(
    "Profile" => isset($_FILES["Profile"]) ? $_FILES["Profile"]["tmp_name"] : null,
    "Firstname" => $_POST["firstname"],
    "Lastname" => $_POST["lastname"],
    "Backup_email" => $_POST["backup"],
    "Strand" => $_POST["Strand"],
  );


foreach ($updatedValues as $key => $value) {
  if ($key === "Profile") {
     
      if (isset($_FILES["Profile"]) && $_FILES["Profile"]["error"] == UPLOAD_ERR_OK) {
          $userInfo[$key] = file_get_contents($_FILES["Profile"]["tmp_name"]);
      } else {
     
          if (isset($_FILES["Profile"]) && $_FILES["Profile"]["error"] != UPLOAD_ERR_OK) {
              echo "Error uploading file.";
          }

      }
  } else {
    
      if (!empty($value) || $value === "0") {
          $userInfo[$key] = $value;
      }
  }
}

$updateSuccess = updateUserProfile($ID_number, $userInfo);

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
    <?php if ($updateSuccess): ?>
        <script>
            alert("Account updated successfully!");
            window.location.href = "http://localhost/WRRL/ADMIN/CODES/facprofile/profile.php?ID=<?php echo $ID_number; ?>";
        </script>
    <?php endif; ?>
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
      <form action="" method="post" enctype="multipart/form-data">
      <div class="Profile-Page">
        <h2>Personal Information</h2>
        <div class="Profileup">
                <label for="UploadProfile" >
                  <img class="profimg" id="Displayimg" src="data:image/jpeg;base64,<?php echo base64_encode($userprofileBlob); ?>">
                </label>
                <input style="display: none;" name="Profile" type="file" id="UploadProfile" accept="image/jpeg, image/png, image/jpg">
                <script>
                    // Corrected getElementById and DisplayImage.src
                    let Profile = document.getElementById('UploadProfile');
                    let DisplayImage = document.getElementById('Displayimg');

                    Profile.onchange = function () {
                        // Check if files are selected
                        if (Profile.files.length > 0) {
                            DisplayImage.src = URL.createObjectURL(Profile.files[0]);
                        }
                    };
                </script>
        </div>

        <div class="ID-number"><label>ID-number</label><span style="color: #828080;"><?php echo $userInfo['ID_number']; ?></span></div>
        <div class="Profile-Info">
          <div class="Info-Column">
            <div class="Info-Item"><label>Firstname:</label><input name="firstname" type="text" value="<?php echo $userInfo['Firstname']; ?>"></div>
            <div class="Info-Item"><label>Lastname:</label><input name="lastname" type="text" value="<?php echo $userInfo['Lastname']; ?>"></div>
            <div class="Info-Item"><label>Email:</label><span style="color: #828080;"><?php echo $userInfo['Liceo_Email']; ?></span></div>
            
          </div>
          
          <div class="Info-Column">
            <div class="Info-Item"><label>Option Email:</label><input name="backup" type="text" value="<?php echo $userInfo['Backup_email']; ?>"></div>

            <div class="Info-Item"><label for="Strand" >Strand:</label>
            <select class="Strand" name="Strand" id="Strand">
              <option ><?php echo $userInfo['Strand']; ?></option>
              <option >STEM</option>
              <option >ABM</option>
              <option >ICT</option>
              <option >HE</option>
            </select>
          </div>

          <div class="Info-Item"><label>Status:</label><span style="color: #828080;"><?php echo $userInfo['Status']; ?></span></div>

        </div>
      </div>
      <input class="savebtn" type="submit" value="Save Changes">
      </form>
  </div>
 </main>

</body>
</html>
