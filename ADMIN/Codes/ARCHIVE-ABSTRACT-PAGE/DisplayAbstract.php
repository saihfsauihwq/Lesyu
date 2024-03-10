<?php

function getResearchPaperDetails($fileDb, $uid) {
    if (isset($uid) && $uid > 0) {
        $uid = $fileDb->real_escape_string($uid);
        $sql = "SELECT * FROM archive_research_info WHERE UID = $uid";
        $result = $fileDb->query($sql);

        if ($result && $result->num_rows > 0) {
            $research = $result->fetch_assoc();
            $title = isset($research['RTitle']) ? $research['RTitle'] : "Title not found";
            $abstract = isset($research['Abstract']) ? $research['Abstract'] : "Abstract not found";
            $date = isset($research['Date']) ? $research['Date'] : "Date not found";
            $authors = isset($research['Authors']) ? $research['Authors'] : "Authors not found";
            $filespath = isset($research['File']) ? $research['File'] : "FilePath not found";
            $Description = isset($research['Description']) ? $research['Description']: "No Description Available";
            $DOI = isset($research['DOI']) ? $research['DOI']: "No DOI Available";
        } else {
            $title = $abstract = $date = $authors  = $filespath  = $Description = $DOI = "Research paper not found";
        }
    } else {
        $title = $abstract = $date = $authors = $filespath = $Description = $DOI = "Invalid request";
    }

    return ['title' => $title, 'abstract' => $abstract, 'date' => $date, 'authors' => $authors, 'filespath' => $filespath, 'Description' => $Description, 'DOI' => $DOI];
}

?>
