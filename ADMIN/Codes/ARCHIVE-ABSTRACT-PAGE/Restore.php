<?php
include 'dbconnect.php';

// Set the response content type to JSON
header('Content-Type: application/json');

function sendResponse($success, $error = null) {
    echo json_encode(['success' => $success, 'error' => $error]);
    exit();
}

if (isset($_GET['uid'])) {
    $uid = $_GET['uid'];

    // Retrieve data from archive_research_info before restoration
    $selectQuery = "SELECT * FROM archive_research_info WHERE UID = ?";
    $stmtSelect = mysqli_prepare($fileDb, $selectQuery);
    mysqli_stmt_bind_param($stmtSelect, "s", $uid);
    mysqli_stmt_execute($stmtSelect);
    $selectResult = mysqli_stmt_get_result($stmtSelect);

    if ($selectResult) {
        $archiveData = mysqli_fetch_assoc($selectResult);

        if ($archiveData) {
            // Insert data back into research_info using prepared statements
            $insertQuery = "INSERT INTO researchfiledb.research_info (UID, RTitle, Authors, Date, Abstract, File, Filename, Strand_ID, Publish_status, DOI, Uploader, Uploader_ID) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmtInsert = mysqli_prepare($fileDb, $insertQuery);
            $escapedAuthors = mysqli_real_escape_string($fileDb, $archiveData['Authors']);

            if ($stmtInsert) {
                mysqli_stmt_bind_param($stmtInsert, "ssssssssssss",
                    $archiveData['UID'], $archiveData['RTitle'], $escapedAuthors,
                    $archiveData['Date'], $archiveData['Abstract'], $archiveData['File'],
                    $archiveData['Filename'], $archiveData['Strand_ID'], $archiveData['Publish_status'],
                    $archiveData['DOI'], $archiveData['Deleter'], $archiveData['Deleter_ID']
                );

                $insertResult = mysqli_stmt_execute($stmtInsert);

                if ($insertResult) {
                    // If insertion is successful, delete the record from archive_research_info
                    $deleteQuery = "DELETE FROM archive_research_info WHERE UID = ?";
                    $stmtDelete = mysqli_prepare($fileDb, $deleteQuery);

                    if ($stmtDelete) {
                        mysqli_stmt_bind_param($stmtDelete, "s", $uid);
                        $deleteResult = mysqli_stmt_execute($stmtDelete);

                        if ($deleteResult) {
                            // If deletion is successful, send a success response
                            sendResponse(true);
                        } else {
                            // If deletion fails, send a failure response
                            sendResponse(false, 'Failed to delete item from archive.');
                        }
                    } else {
                        // If prepared statement creation fails, send a failure response
                        sendResponse(false, 'Failed to create delete statement.');
                    }
                } else {
                    // If insertion fails, send a failure response
                    sendResponse(false, 'Failed to restore item. Insertion failed.');
                }
                mysqli_stmt_close($stmtInsert);
            } else {
                // If prepared statement creation fails, send a failure response
                sendResponse(false, 'Failed to create restore statement.');
            }
        } else {
            // If no data found, send a failure response
            sendResponse(false, 'No data found in archive for UID: ' . $uid);
        }
    } else {
        // If data retrieval fails, send a failure response
        sendResponse(false, 'Failed to retrieve data from archive for restoration.');
    }
} else {
    // If UID is not provided, send a failure response
    sendResponse(false, 'UID not provided.');
}
?>
