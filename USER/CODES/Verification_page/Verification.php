<?php
session_start();

include '/xampp/htdocs/WRRL/USER/CODES/Verification_page/verfication_process.php';

$email = $_GET['email'] ?? ''; // Assuming email is sent via GET parameter
$verificationError = ''; // Initialize the verification error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $verification_code = $_POST['verification_code'] ?? '';

    if ($verification_code) {
        $result = verifyCode($email, $verification_code);

        if ($result === true) {
            // Verification successful, redirect to login page
            echo '<script>';
            echo 'alert("Account registered successfully!");';
            echo 'window.location.href = "http://localhost/WRRL/USER/Codes/LOGIN-PAGE/LOGIN-PAGE.php";';
            echo '</script>';
            exit();
        } else {
            // Verification failed, set the error message
            $verificationError = $result;
        }
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
        </div>
    </header>
      
      <main>
          <div class="Container">
              <div class="Reg-Form">
                  <h2>Verification Code</h2>
                  <p>Please enter the verification code we sent to your email: </p>
                  <p class="Mail"><?php echo htmlspecialchars($email); ?></p>
                  <p class="error" style=" color: rgb(164,0,1);"
                  ><?php echo htmlspecialchars($verificationError); ?></p>
                  <div class="form">
                      <form action="" method="post">
                          <label for="verificationCode"></label>
                          <input type="text" id="verificationCode" name="verification_code" placeholder="Enter verification code">
                          <input type="submit" value="Continue">
                      </form>
                  </div>
              </div>
          </div>
      </main>
    </body>
  </html>