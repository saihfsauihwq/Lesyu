<?php
include("getDB.php");
include("Strands.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $strandId = $_POST['Strand'];
    $title = $_POST['Title'];
    $authors = $_POST['Authors'];
    $date = $_POST['Date'];
    $abstract = $_POST['Abstract'];
    $introduction = $_POST['Introduction'];

    $fileContent = file_get_contents($_FILES['file']['tmp_name']);

    $query = "INSERT INTO your_table_nam (Strand_ID, Title, Authors, Date, Abstract, Introduction, FileContent) 
              VALUES ('$strandId', '$title', '$authors', '$date', '$abstract', '$introduction', ?)";
    
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "s", $fileContent);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "Data has been successfully inserted.";
    } else {
        echo "Error: " . mysqli_error($db);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($db);
}
?>