<?php
function Forgotpassword() {
    $error = ""; // Initialize the error variable

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "login_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["Email"]) && isset($_GET["ID"])) {
        $email = $_GET["Email"];
        $ID = $_GET["ID"];

        $emailQuery = "SELECT * FROM fac_accounts WHERE `Liceo_Email` = ?";
        $stmtEmail = $conn->prepare($emailQuery);
        $stmtEmail->bind_param("s", $email);
        $stmtEmail->execute();
        $emailResult = $stmtEmail->get_result();

        $idQuery = "SELECT * FROM user_accounts WHERE `ID_number` = ?";
        $stmtID = $conn->prepare($idQuery);
        $stmtID->bind_param("s", $ID);
        $stmtID->execute();
        $idResult = $stmtID->get_result();

        // Fetch the rows to determine if the records exist
        $emailRow = $emailResult->fetch_assoc();
        $idRow = $idResult->fetch_assoc();

        if ($emailRow && $idRow) {
            // Redirect to resetpassword.php
            header("Location: /WRRL/FAC/CODES/Resetpassword/resetpass.php?ID=$ID");
            exit();
        } elseif (!$emailRow && !$idRow) {
            $error = "ID-number and Email address not found.";
        } elseif (!$emailRow) {
            $error = "Email address not found";
        } elseif (!$idRow) {
            $error = "ID-number not found.";
        }
    }

    // Close the connection
    $conn->close();

    return $error; // Return the error message
}

// Call the function to get the error message
$error = Forgotpassword();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="Author" content="John Lloyd Navarro">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Research Papers repository For Liceo">
    <meta name="keywords" content="Research, Repository, Liceo, University, Admin interface">
    <title>Web-Based Research Repository</title>
    <link rel="stylesheet" href="General.css">
    <link rel="stylesheet" href="Header.css">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="shake.css">
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
        <div class="Form">
            <h2>Forgot password</h2>
            <p>Enter your ID-number and Liceo Email to reset your password</p>
            <form action="" method="get">
                <label for="ID">ID-number</label>
                <input type="text" id="ID" name="ID" placeholder="Enter ID-number" required>
                <label for="Email">Liceo Email</label>
                <input type="text" id="Email" name="Email" placeholder="Enter Liceo Email" required>
                <?php if (!empty($error)) : ?>
                    <div class="error"><?php echo $error; ?></div>
                <?php endif; ?>
                <input type="submit" value="Continue">
            </form>
        </div>
    </div>
</main>

</body>
</html>
