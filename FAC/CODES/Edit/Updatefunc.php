<?php
include("dbconnect.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $uid = isset($_POST['uid']) ? $_POST['uid'] : null;


    $uid = mysqli_real_escape_string($fileDb, $uid);


    $newTitle = mysqli_real_escape_string($fileDb, $_POST['RTitle']);
    $newAuthors = mysqli_real_escape_string($fileDb, $_POST['Authors']);
    $newDate = mysqli_real_escape_string($fileDb, $_POST['Date']);
    $newAbstract = mysqli_real_escape_string($fileDb, $_POST['Abstract']);
    $newStrand = mysqli_real_escape_string($fileDb, $_POST['Strand']);
    $newDescription = mysqli_real_escape_string($fileDb, $_POST['Description']); 


    $updateSql = "UPDATE research_info SET RTitle = '$newTitle', Authors = '$newAuthors', Date = '$newDate', Abstract = '$newAbstract', Strand_ID = '$newStrand', Description = ' $newDescription' WHERE UID = '$uid'";


    $updateResult = $fileDb->query($updateSql);

    if ($updateResult) {
        echo "<script>alert('Update successful!'); window.location.href = 'http://localhost/WRRL/FAC/Codes/ABSTRACT-PAGE/ABSTRACT-PAGE.php?uid=$uid';</script>";
        exit();
    } else {

        echo "Error updating record: " . $fileDb->error;
    }
} else {

}
?>
