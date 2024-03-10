<?php
include 'dbconnect.php';
include 'DisplayAbstract.php';

if (isset($_GET['uid']) && $_GET['uid'] > 0) {
    $uid = $_GET['uid'];

    $researchDetails = getResearchPaperDetails($fileDb, $uid);

    if ($researchDetails) {
        if (isset($researchDetails['filespath'])) {
            $file_content = $researchDetails['filespath'];

            // Set appropriate headers for PDF viewing
            header('Content-Type: application/pdf');
            header('Content-Length: ' . strlen($file_content));

            // Output the file content
            echo $file_content;
            exit;
        } else {
            echo 'File content not found in the retrieved data.';
        }
    } else {
        echo 'Invalid request or file information not found.';
    }
} else {
    echo 'Invalid request.';
}
?>
