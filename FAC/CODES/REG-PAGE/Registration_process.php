<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '/xampp/htdocs/WRRL/PHPMailer/src/Exception.php';
require '/xampp/htdocs/WRRL/PHPMailer/src/PHPMailer.php';
require '/xampp/htdocs/WRRL/PHPMailer/src/SMTP.php';

function sendVerificationEmail($to, $subject, $message) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'jhonllyodnavarro35@gmail.com'; // Replace with your SMTP username
        $mail->Password = 'qaol skoc symg ljbj'; // Replace with your SMTP password
        $mail->SMTPSecure = 'tls'; // Change to 'ssl' if needed
        $mail->Port = 587; // Adjust the port if needed

        //Recipients
        $mail->setFrom('jhonllyodnavarro35@gmail.com', 'Jhonlloyd Navarro');
        $mail->addAddress($to);

        //Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}


function generateVerificationCode() {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $verificationCode = '';

    for ($i = 0; $i < 7; $i++) {
        $verificationCode .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $verificationCode;
}


function Registration() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "login_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $required_fields = ['Firstname','Lastname', 'ID-number', 'Email', 'Password', 'CPassword'];

        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                return "Please fill in all required fields.";
            }
        }

        $Profile = mysqli_real_escape_string($conn, $_POST["Profile"]);
        $Firstname = mysqli_real_escape_string($conn, $_POST["Firstname"]);
        $Lastname = mysqli_real_escape_string($conn, $_POST["Lastname"]);
        $ID_number = mysqli_real_escape_string($conn, $_POST["ID-number"]);
        $Gender = mysqli_real_escape_string($conn, $_POST["Gender"]);
        $Strand = mysqli_real_escape_string($conn, $_POST["Strand"]);
        $email = mysqli_real_escape_string($conn, trim($_POST["Email"]));

        $email_parts = explode("@", $email);
        $user_domain = '';

        if (is_array($email_parts) && count($email_parts) > 0) {
            $user_domain = strtolower(end($email_parts));
        } else {
            $user_domain = '';
        }

        if (isset($_FILES['Profile']) && $_FILES['Profile']['error'] == 0) {

            echo "Debug: File details - " . print_r($_FILES['Profile'], true);

            $Profile = mysqli_real_escape_string($conn, file_get_contents($_FILES['Profile']['tmp_name']));

            
            echo "Debug: Profile data length - " . strlen($Profile);
        }

        $password = mysqli_real_escape_string($conn, $_POST["Password"]);
        $confirm_password = mysqli_real_escape_string($conn, $_POST["CPassword"]);

        if ($password === $confirm_password) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $allowed_domain = "liceo.edu.ph";

            if ($user_domain === $allowed_domain) {
                $sql = "INSERT INTO fac_accounts (Profile,Firstname, Lastname, ID_number, Liceo_Email, Password, Strand, Gender) 
                        VALUES 
                        ('$Profile','$Firstname', '$Lastname', '$ID_number', '$email', '$hashed_password', '$Strand', '$Gender')";

                if ($conn->query($sql) === TRUE) {
                    // Generate and store verification code
                    $verification_code = generateVerificationCode();
                    $sql = "UPDATE fac_accounts SET Verification_code = '$verification_code' WHERE Liceo_Email = '$email'";
                    $conn->query($sql);

                    // Send verification email using PHPMailer
                    $subject = 'Email Verification Code';
                    $message = "Your verification code is: $verification_code";

                    $result = sendVerificationEmail($email, $subject, $message);

                    if ($result === true) {
                        return true;
                    } else {
                        return $result;
                    }
                } else {
                    return "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                return "Invalid email, Please use Liceo corporate email.";
            }
        } else {
            return "Password do not match, please try again.";
        }
    }

    $conn->close();
    return false;   
}

?>
