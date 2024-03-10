<?php

function Displayinfo($fileDb, $uid) {
    if (isset($uid) && $uid > 0) {
        $uid = $fileDb->real_escape_string($uid);
        $sql = "SELECT * FROM research_info WHERE UID = $uid";
        $result = $fileDb->query($sql);

        if ($result && $result->num_rows > 0) {
            $research = $result->fetch_assoc();
            $title = isset($research['RTitle']) ? $research['RTitle'] : "Title not found";
            $abstract = isset($research['Abstract']) ? $research['Abstract'] : "Abstract not found";
            $date = isset($research['Date']) ? $research['Date'] : "Date not found";
            $authors = isset($research['Authors']) ? $research['Authors'] : "Authors not found";
            $files = isset($research['Filename']) ? $research['Filename'] : "FilePath not found";
            $strandID = isset($research['Strand_ID']) ? $research['Strand_ID'] : "Strand not found";
            $Description = isset($research['Description']) ? $research['Description']: "NO Description found.";
        } else {
            $title = $abstract = $date = $authors = $files = $strandID = $Description = "Research paper not found";
        }
    } else {
        $title = $abstract = $date = $authors  = $files = $strandID = $Description = "Invalid request";
    }

    return ['title' => $title, 'abstract' => $abstract, 'date' => $date, 'authors' => $authors, 'files' => $files, 'strands_id' => $strandID, 'Description' => $Description];
}

?>
