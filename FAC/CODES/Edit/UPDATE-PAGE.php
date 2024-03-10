<?php
include("Strands.php");
include("dbconnect.php");
include("Displayinfo.php");
include("Updatefunc.php");

$researchDetails = array(
    'title' => '',
    'abstract' => '',
    'filespath' => '',
    'date' => '',
    'authors' => ''
);


if (isset($_GET['uid']) && $_GET['uid'] > 0) {
    $uid = $_GET['uid'];
 
    $researchDetails = Displayinfo($fileDb, $uid);

    // Extract data from the fetched result
    $strandID = $researchDetails['strands_id'];
    $title = $researchDetails['title'];
    $authors = $researchDetails['authors'];
    $date = $researchDetails['date'];
    $abstract = $researchDetails['abstract'];
    $files = $researchDetails['files'];
    $Description = $researchDetails['Description'];
} else {
    // Invalid UID, handle accordingly (redirect, show error, etc.)
    header("Location: http://localhost/WRRL/FAC/Codes/HOME-PAGE/HOME-PAGE.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="Author" content="Jhon llyod Navarro">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Research Papers repository For Liceo">
    <meta name="keywords" content="Research, Repository, Liceo, University, Admin interface">
    <title>Web-Based Research Repository</title>
    <link rel="stylesheet" href="General.css">
    <link rel="stylesheet" href="Header.css">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="Searchbar.css">
    <link rel="stylesheet" href="nav.css">
    <link rel="Icon" href="/WRRL/IMAGES/favicon.png">
    <script src="selectfile.js"></script>
    <script src="dragndrop.js"></script>
    <style>
        #drop-area {
            width: 320px;
            border: 2px dashed rgb(164,0,1);
            border-radius: 8px;
            text-align: center;
            padding: 45px 20px 45px 20px ;
            margin: 20px auto;
            cursor: pointer;
        }
        #drop-area.drag-over {
            background-color: rgb(253, 197, 65);
            color: whitesmoke;
        }
    </style>
</head>
<body>

<header>
        <div class="Header-background">
            <div class="Logo">
                <a href="/WRRL/FAC/Codes/HOME-PAGE/HOME-PAGE.php">
                    <img class="Logo-icon" src="/WRRL/IMAGES/mainlogo.png">
                </a>
            </div>
            <div class="Title">
                <p class="Name">Liceo U Repository</p>
                <p class="line">Committed to Total Human Formation!</p>
            </div>
    </header>


<main>
    <form action="Updatefunc.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="uid" value="<?php echo $uid; ?>">
        <div class="container">
            <div class="column1">
            <label class="strandHead1">Strand</label>
                    <select class="Strand" name="Strand">
                        <?php
                        $strands = getStrands();
                        foreach ($strands as $strand) {
                        $selected = ($strand['Strand_ID'] == $strandID) ? 'selected' : '';
                        echo "<option value='{$strand['Strand_ID']}' $selected>{$strand['Strand_name']}</option>";
                        }
                        ?>
                    </select>


                    <label class="Head1" for="Title">Title</label>
                    <textarea class="Title-input" placeholder="Enter research title..." id="Title" name="RTitle" rows="3"><?php echo $title; ?></textarea>

                    <label class="Head1" for="Authors">Author/s</label>
                    <textarea class="Authors" placeholder="List of Authors..." id="Authors" name="Authors" rows="3">
                    <?php echo isset($researchDetails['authors']) ? $researchDetails['authors'] : ''; ?>
                    </textarea>

                    <label class="Head1" for="Date">Date</label>
                    <input class="Date" id="Date" type="date" name="Date" value="<?php echo $date; ?>">

                    <label class="Head1">Attach File</label>
                    <input class="file" id="file" type="file" name="file" accept="application/pdf" onchange="updateLabel()"/>
                    <label class="Upload-Here" for="file" id="drop-area"><?php echo $files; ?></label>

                    <button class="Save" id="Publish" type="submit" name="Title">Save Changes</button>
            </div>

                <div class="column2">
                    <label class="Head1" for="Abstract">Abstract</label>
                    <textarea class="Abstract" placeholder="Enter Abstract..." id="Abstract" name="Abstract" rows="30"><?php echo $abstract; ?></textarea>

                    <label class="Head1" for="Description">Description</label>
                    <textarea class="Description" placeholder="Enter Keywords..." id="Description" name="Description" rows="4"><?php echo $Description; ?></textarea>
                </div>
        </div>
    </form>
</main>

</body>
</html>
