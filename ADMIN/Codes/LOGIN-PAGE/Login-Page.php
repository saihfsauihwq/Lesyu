<?php
session_start();
?>

<!Doctype html>
  <html lang="en">
    <head>
      <meta charset= UTF-8>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Web-Based Research Repository</title>
      <link rel="Stylesheet" href="General.css">
      <link rel="Stylesheet" href="Header.css">
      <link rel="Stylesheet" href="main.css">
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
        <div class="Log-box">
            <form action="Process.php" method="post">
                <div class="Log-in-Container">
                    <label for="user_id" class="login-Header" style="margin-bottom: 15px;">Log in</label>
                    <img class="show" id="show" src="/WRRL/IMAGES/lock.png">
                    <input id="user_id" class="Username-input" type="text" name="user_id" placeholder="User-ID" required>
                    <input id="password" class="Password-input" type="password" name="password" placeholder="Password" required>
                    <div class="error-message" style="color: red; font-size: 14px; margin-bottom: 20px;">
                      <?php
                      if (isset($_SESSION["error_message"])) {
                          echo '<div class="error-message">' . $_SESSION["error_message"] . '</div>';
                          unset($_SESSION["error_message"]);
                      }
                      ?>
                    </div>
                    <div>
                        <input class="Login-button" type="submit" value="Log in">
                    </div>
                </div>
            </form>
        </div>
        <script>
          let show = document.getElementById('show');

          let password = document.getElementById('password');

          show.addEventListener('click', function() {
            if (password.type === 'password') {
              password.type = 'text';
              show.src = '/WRRL/IMAGES/Unlock.png';
            } else {
              password.type = 'password';
              show.src = '/WRRL/IMAGES/lock.png';
            }
          });
          
        </script>
      </main>
    </body>
  </html>