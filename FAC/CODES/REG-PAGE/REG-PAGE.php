<?php
session_start();

include 'Registration_process.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $result = Registration();

    if ($result === true) {
        $email = $_POST["Email"]; // Define $email before using it
        $_SESSION['Email'] = $email;
        header("Location: http://localhost/WRRL/FAC/Codes/Verification_page/Verification.php?email=" . urlencode($email));
        exit();
    } else {
        $_SESSION['error_message'] = $result;
        header("Location: REG-PAGE.php");
        exit();
    }
}

// Display error and success messages
$error_message = $_SESSION['error_message'] ?? '';
unset($_SESSION['error_message']);

$success_message = $_SESSION['success_message'] ?? '';
unset($_SESSION['success_message']);
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
        <form action="REG-PAGE.php" method="post" enctype="multipart/form-data">
          <div class="Reg-Form">
            <div class="Reg-Form-Title">
              <h1>Register</h1>
            </div>
            
            <?php if (!empty($error_message)): ?>
                        <div class="error"><?php echo $error_message; ?></div>
              <?php endif; ?>
            <div class="Reg-Form-Body">
              <div class="Column3">
                <div class="Profileup">
                    <label for="UploadProfile" style="Margin: 0 0 17px 0;">
                        <img id="Displayimg" src="/WRRL/FAC/Images/profile-icon-png-898.png"><br>Select Profile
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

                <label for="name" style="Margin-top: 0;">Firstname</label>
                <input id="name"  Name="Firstname" class="type-box" type="text" placeholder="-">

                <label for="name">Lastname</label>
                <input id="name" name="Lastname" class="type-box" type="text" placeholder="-">

              </div>

              <div class="column1">

                <label for="ID-number">ID-number</label>
                <input id="ID-number" name="ID-number" class="type-box" type="text" placeholder="-">

                  <div class="info">
                    <label for="name">Gender:</label>
                    <select class="gender" name="Gender" id="Gender">
                      <option >-</option>
                      <option >Male</option>
                      <option >Female</option>
                    </select>
                  </div>

                  <label for="Password">Password</label>
                  <input id="Password" name="Password" class="type-box" type="Password" placeholder="-">

              </div>

                <div class="column2">

                  <label for="Email">Liceo Email</label>
                  <input id="Email" name="Email" class="type-box" type="Email" placeholder="-">

                  <div class="info">
                    <label for="Strand">Strand:</label>
                    <select class="Strand" name="Strand" id="Strand">
                    <option >-</option>
                      <option >STEM</option>
                      <option >ABM</option>
                      <option >ICT</option>
                      <option >HE</option>
                    </select>
                  </div>


                  <label for="CPassword">Confirm Password</label>
                  <input id="CPassword" name="CPassword" class="type-box" type="Password" placeholder="-">

                </div> 
            </div>
            <div class="checkbox">
              <label>
                <p class="terms">By clicking Sign Up, you agree with the <a href="http://localhost/WRRL/USER/Codes/Terms/Terms.php">Terms & Conditions</a>.</p>
              </label>
            </div>
            <div class="signupbtn">
              <input class="Sign_up" type="submit" value="Sign up" class="submit-button">
            </div>
            <div>
              <p class="login">Already have an account? <a href="http://localhost/WRRL/USER/Codes/LOGIN-PAGE/LOGIN-PAGE.php" style="color: rgb(164,0,1);">Login</a></p>
            </div>
            
          </div>
        </form>
        </div>
      </main>
    </body>
  </html>