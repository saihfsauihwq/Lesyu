<?php
session_start();
include("Displayinfo.php");
include("Updateinfo.php");

if (!isset($_SESSION["user_accounts"])) {
    header("Location: http://localhost/WRRL/USER/Codes/LOGIN-PAGE/LOGIN-PAGE.php");
    exit();
}

$ID_number = $_SESSION["user_accounts"]["ID_number"];
$userInfo = getUserInfo($ID_number);

if ($userInfo) {
    $userprofileBlob = $userInfo["Profile"];
}

$updateSuccess = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ID_number = $_SESSION["user_accounts"]["ID_number"];
    $userInfo = getUserInfo($ID_number);

    // Get the updated values from the form
// Get the updated values from the form
$updatedValues = array(
  "Profile" => isset($_FILES["Profile"]) ? $_FILES["Profile"]["tmp_name"] : null,
  "Firstname" => $_POST["firstname"],
  "Lastname" => $_POST["lastname"],
  "Backup_email" => $_POST["backup"],
  "Grade_Section" => $_POST["Gradesec"],
  "Strand" => $_POST["Strand"],
  "EStatus" => $_POST["Status"],
  "Year_grad" => $_POST["year"]
);

// Update only the fields that have changed
foreach ($updatedValues as $key => $value) {
  if ($key === "Profile") {
      // Check if the file upload was successful
      if (isset($value) && $_FILES["Profile"]["error"] == UPLOAD_ERR_OK) {
          $userInfo[$key] = file_get_contents($value);
      } else {
          // Handle the case where the file upload was not successful
          // You might want to display an error message or take appropriate action
          echo "Error uploading file.";
      }
  } else {
      // Handle other fields
      if (!empty($value) || $value === "0") {
          $userInfo[$key] = $value;
      }
  }
}

// Update the user information in the database
$updateSuccess = updateUserProfile($ID_number, $userInfo);

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
            window.location.href = "http://localhost/WRRL/USER/Codes/Profile/Profile.php";
        </script>
    <?php endif; ?>
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
      <li style=" background-color: rgba(148, 147, 144, 0.153);" ><a class="nav_bar" href="http://localhost/WRRL/USER/Codes/Profile/Profile.php">Profile</a></li>
      <li><a class="nav_bar" href="http://localhost/WRRL/USER/Codes/Update/Update.php">Update Account</a></li>
      <li><a class="nav_bar" href="http://localhost/WRRL/USER/Codes/LOGIN-PAGE/LOGIN-PAGE.php">Logout</a></li>
    </ul>
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
            <div class="Info-Item"><label>Option Email:</label><input name="backup" type="text" value="<?php echo $userInfo['Backup_email']; ?>"></div>
          </div>

          <div class="Info-Column">
            <div class="Info-Item"><label>Grade Section:</label><input name="Gradesec" type="text" value="<?php echo $userInfo['Grade_Section']; ?>"></div>
            <div class="Info-Item"><label for="Strand" >Strand:</label>
            <select class="Strand" name="Strand" id="Strand">
              <option ><?php echo $userInfo['Strand']; ?></option>
              <option >STEM</option>
              <option >ABM</option>
              <option >ICT</option>
              <option >HE</option>
            </select>
          </div>

          <div class="Info-Item"><label for="status">Status:</label>
            <select name="Status" id="status" value="<?php echo $userInfo['EStatus']; ?>">
              <option value="Student">Student</option>
              <option value="Faculty">Faculty</option>
              <option value="Alumni">Alumni</option>
            </select>
          </div>


          <div class="Info-Item">
              <label for="Year">Year graduated:</label>
              <select class="Year" name="year" id="Year">
                  <option value="Present">Present</option>
                  <?php
                  for ($year = 1999; $year <= 2099; $year++) {
                      if ($year == $userInfo['Year_grad']) {
                          echo '<option value="' . $year . '" selected>' . $year . '</option>';
                      } else {
                          echo '<option value="' . $year . '">' . $year . '</option>';
                      }
                  }
                  ?>
              </select>
          </div>
        </div>
      </div>
      <input class="savebtn" type="submit" value="Save Changes">
      </form>
  </div>
 </main>

</body>
</html>
