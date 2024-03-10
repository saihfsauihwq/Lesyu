<?php
include 'dbconnect.php';

$debuggingEnabled = true; // Set to true for debugging, false for production

if (isset($_GET['uid'])) {
    $uid = $_GET['uid'];

    // Retrieve data from research_info before deletion
    $selectQuery = "SELECT * FROM research_info WHERE UID = ?";
    $stmtSelect = mysqli_prepare($fileDb, $selectQuery);
    mysqli_stmt_bind_param($stmtSelect, "s", $uid);
    mysqli_stmt_execute($stmtSelect);
    $selectResult = mysqli_stmt_get_result($stmtSelect);

    if ($selectResult) {
        $researchData = mysqli_fetch_assoc($selectResult);

        // Insert data into archive_research_info using prepared statements
        $insertQuery = "INSERT INTO archivedb.archive_research_info (UID, Archiving_date, RTitle, Authors, Date, Abstract, File, Filename, Description, Strand_ID, Publish_status, DOI, Deleter, Deleter_ID) 
                        VALUES (?, NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmtInsert = mysqli_prepare($fileDb, $insertQuery);
        $escapedAuthors = mysqli_real_escape_string($fileDb, $researchData['Authors']);

        if ($stmtInsert) {
            $RTitle = isset($researchData['RTitle']) ? $researchData['RTitle'] : 'DefaultRTitle';

            mysqli_stmt_bind_param($stmtInsert, "sssssssssssss",
                $researchData['UID'], $RTitle, $escapedAuthors,
                $researchData['Date'], $researchData['Abstract'], $researchData['File'],
                $researchData['Filename'], $researchData['Description'], $researchData['Strand_ID'], $researchData['Publish_status'],
                $researchData['DOI'], $researchData['Uploader'], $researchData['Uploader_ID']
            );


            $insertResult = mysqli_stmt_execute($stmtInsert);

            if ($insertResult) {
                // If archiving is successful, delete the record from research_info
                $deleteQuery = "DELETE FROM research_info WHERE UID = ?";
                $stmtDelete = mysqli_prepare($fileDb, $deleteQuery);

                if ($stmtDelete) {
                    mysqli_stmt_bind_param($stmtDelete, "s", $uid);
                    $deleteResult = mysqli_stmt_execute($stmtDelete);

                    if ($deleteResult) {
                        // If deletion is successful, send a success response
                        echo json_encode(['success' => true]);
                    } else {
                        // If deletion fails, send a failure response
                        echo json_encode(['success' => false, 'error' => 'Failed to delete item.']);
                    }
                } else {
                    // If prepared statement creation fails, send a failure response
                    echo json_encode(['success' => false, 'error' => 'Failed to create delete statement.']);
                }
            } else {
                // If archiving fails, send a failure response
                echo json_encode(['success' => false, 'error' => 'Failed to archive item.']);
            }
            mysqli_stmt_close($stmtInsert);
        } else {
            // If prepared statement creation fails, send a failure response
            echo json_encode(['success' => false, 'error' => 'Failed to create archive statement.']);
        }
    } else {
        // If data retrieval fails, send a failure response
        echo json_encode(['success' => false, 'error' => 'Failed to retrieve data for archiving.']);
    }
} else {
    // If UID is not provided, send a failure response
    echo json_encode(['success' => false, 'error' => 'UID not provided.']);
}
?>
