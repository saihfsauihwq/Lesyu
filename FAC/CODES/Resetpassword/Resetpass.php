<?php
include 'reset.php';
session_start();

$ID_number = isset($_GET['ID']) ? $_GET['ID'] : '';
$passwordUpdated = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the new password from the form
    $newPassword = $_POST['pass'];
    $confirmPassword = $_POST['Cpass'];

    if (!empty($newPassword) && $newPassword == $confirmPassword) {
      if (!empty($ID_number)) {
          updatePassword($ID_number, $newPassword); // Pass the correct parameters
          $passwordUpdated = true;
      } else {
          $errorMessage = "Invalid ID number.";
      }
  } else {
      $errorMessage = "Password didn't match.";
  }
}
?>

<!Doctype html>
  <html lang="en">
    <head>
      <meta charset= UTF-8>
      <meta name="Author" content="Jhon llyod Navarro">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="description" content="Research Papers repository For Liceo">
      <meta name="keywords" content="Research, Repository, Liceo, University, Admin interface">
      <title>Web-Based Research Repository</title>
      <link rel="Stylesheet" href="General.css">
      <link rel="Stylesheet" href="Header.css">
      <link rel="Stylesheet" href="main.css">
      <link rel="Stylesheet" href="shake.css">
      <link rel="Icon" href="/WRRL/IMAGES/favicon.png">
      <script>
        window.onload = function() {
            <?php
            if ($passwordUpdated) {
                echo 'alert("Password Changed successfully!");';
                echo 'window.location.href = "/WRRL/FAC/Codes/LOGIN-PAGE/LOGIN-PAGE.php";';
            }
            ?>
        };
    </script>
    </head>
    <body>

    <header>
        <div class="Header-background">
            <div class="Logo">
                    <img class="Logo-icon" src="/WRRL/IMAGES/mainlogo.png">
            </div>
            <div class="Title">
                <p class="Name">Liceo U Repository</p>
                <p class="line">Committed to Total Human Formation!</p>
            </div>
    </header>
      
      <main>
          <div class="Container">
            <div class="Form">
              <h2>Reset password</h2>
              <p>Enter your ID-number and Liceo Email to reset your password<p>
              <form action="Resetpass.php?ID=<?php echo $ID_number; ?>" method="post">
                <label for="pass">New password</label>
                <input type="password" id="pass" name="pass" placeholder="Enter new password">
                <label for="Cpass">Confirm Password</label>
                <input type="password" id="Cpass" name="Cpass" placeholder="Confirm password">
                <div class="error"><?php echo isset($errorMessage) ? $errorMessage : ""; ?></div>
                <input type="submit" value="Continue">
              </form>    
            </div>
          </div>
      </main>
    </body>
  </html>