<?php

function fileupload() {
    $DbHost = "localhost";
    $DbUsername = "root";
    $DbPassword = "";
    $DbName = "researchfiledb";

    $fileDb = mysqli_connect($DbHost, $DbUsername, $DbPassword, $DbName);

    if (!$fileDb) {
        die("Connection failed: " . mysqli_connect_error());
    }

    return $fileDb;
}

$fileDb = fileupload();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $strandId = $_POST['Strand'];
    $title = $_POST['RTitle'];
    $authors = explode(', ', $_POST['Authors']);
    $date = $_POST['Date'];
    $abstract = $_POST['Abstract'];
    $Description = $_POST['Description'];
    $pubstat = $_POST['pubstat'];
    $DOI = $_POST['DOI'];

    if ($_POST['pubstat'] === 'Not Published') {
        $DOI = 'No DOI Available';
    }

    $Filename = $_FILES['file']['name'];
    $fileContent = file_get_contents($_FILES['file']['tmp_name']);

    $authorsString = implode(', ', $authors);

    $query = "INSERT INTO research_info (Strand_ID, RTitle, Authors, Date, Abstract, Filename, File, Description, Publish_status, DOI) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($fileDb, $query);

    if (!$stmt) {
        die("Error in prepared statement: " . mysqli_error($fileDb));
    }

    mysqli_stmt_bind_param($stmt, "isssssssss", $strandId, $title, $authorsString, $date, $abstract, $Filename, $fileContent, $Description, $pubstat, $DOI);
    
    if (mysqli_stmt_execute($stmt)) {
        echo '<script>alert("Upload Complete!");</script>';
        echo '<script>window.location = "PUBLISH-PAGE.php";</script>';
        exit;
    } else {
        echo "Error: " . mysqli_error($fileDb);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($fileDb);
}
?>
