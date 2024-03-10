<?php
session_start();
?>

<!Doctype html>
  <html lang="en">
    <head>
      <meta charset= UTF-8>
      <meta name="Author" content="Jhon llyod Navarro">
      <meta name="viewport" content="width=device-width, initial- scale=1.0">
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

        <div class="Log-box">
          <form action="Login_process.php" method="post">
            <div class="Log-in-Container">
              <label class="login-Header">Log in</label>

              <img class="shownhide" id="shownhide" src="/WRRL/IMAGES/lock.png"> 

              <input class="ID-number-input" type="text" name="ID-number" placeholder="Enter ID-number or Liceo email">
              <input class="Password-input" id="password" type="password" name="password" placeholder="Password">
              <div class="error" >
              <?php
                      if (isset($_SESSION["error_message"])) {
                          echo '<div>' . $_SESSION["error_message"] . '</div>';
                          unset($_SESSION["error_message"]);
                      }
                      ?>
              </div>
              <div>
                <button class="Login-button" type="submit">Login</button>
                <a class="Forgot-password-button" href="/WRRL/USER/CODES/forgotpassword/forgotpass.php">Forgot password?</a>
                <p class="Signin-con">Already have an account? <a class="Signin" href="http://localhost/WRRL/USER/Codes/REG-PAGE/REG-PAGE.php" >Register</a></p>
              </div>
            </div>
          </form>
        </div>
        <script>
          let shownhide = document.getElementById('shownhide');

          let password = document.getElementById('password');

          shownhide.addEventListener('click', function() {
            if (password.type === 'password') {
              password.type = 'text';
              shownhide.src = '/WRRL/IMAGES/Unlock.png';
            } else {
              password.type = 'password';
              shownhide.src = '/WRRL/IMAGES/lock.png';
            }
          });
          
        </script>
      </main>
    </body>
  </html>