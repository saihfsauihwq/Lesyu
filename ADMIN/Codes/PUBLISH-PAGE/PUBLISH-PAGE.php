<?php
session_start();
include("getDB.php");
include("Strands.php");
include("Upload.php");

if (!isset($_SESSION["admin_accounts"])) {
  header("Location: Login-Page.php");
  exit();
}
$userId = $_SESSION["admin_accounts"]["USER-ID"];
$adminName = getAdminName($userId);

$DbHost = "localhost";
$DbUsername = "root";
$DbPassword = "";
$DbName = "researchfiledb";

$fileDb = mysqli_connect($DbHost, $DbUsername, $DbPassword, $DbName);

if (!$fileDb) {
    die("Connection failed: " . mysqli_connect_error());
}

$latestVolumeQuery = "SELECT MAX(YEAR(Date)) AS LatestVolume FROM research_info";
$latestVolumeResult = mysqli_query($fileDb, $latestVolumeQuery);

if ($latestVolumeResult) {
    $latestVolumeRow = mysqli_fetch_assoc($latestVolumeResult);
    $latestVolume = $latestVolumeRow['LatestVolume'];
    $selectedVolume = $latestVolume;
} else {
    // Handle the error if needed
    $selectedVolume = null;
}

mysqli_close($fileDb);
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
            width: 300px;
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
            <a href="/WRRL/ADMIN/Codes/HOME-PAGE/HOME-PAGE.php">
                <img class="Logo-icon" src="/WRRL/IMAGES/mainlogo.png">
            </a>
        </div>
        <div class="Title">
            <p class="Name">Liceo U Repository</p>
            <p class="line">Committed to Total Human Formation!</p>
        </div>
</header>

<nav>
    <div class="Navigation-bar-container">
        <div class="profile">
            <div class="Profile-picture">
                <img class="Ppic" src="Images/gojo.jpg">
                <a class="Username"> <?php echo $adminName; ?> </a>
            </div>
            <div class="nav-buttons">
                <div><img src="/WRRL/IMAGES/house-window.png"><a class="home" href="http://localhost/WRRL/ADMIN/Codes/HOME-PAGE/HOME-PAGE.php">Home</a></div>
                <div><img src="/WRRL/IMAGES/books.png" ><a class="publish" href="/WRRL/ADMIN/Codes/Volume/volume.php?volume=<?php echo $selectedVolume; ?>">Volumes</a></div>
                <div><img src="/WRRL/IMAGES/file-invoice.png    "><a class="publish" href="http://localhost/WRRL/ADMIN/Codes/PUBLISHLIST/Publishlist.php">Uploads</a></div>
                <div><img src="/WRRL/IMAGES/file-upload.png"><a style="color: rgb(164,0,1); text-decoration: underline;" class="publish" href="/WRRL/ADMIN/Codes/PUBLISH-PAGE/PUBLISH-PAGE.php">Upload new paper</a></div>
                <div><img src="/WRRL/IMAGES/user.png"><a class="publish" href="/WRRL/ADMIN/Codes/USERLIST/USERLIST.php">List of Accounts</a></div>
                <div><img src="/WRRL/IMAGES/folders.png"><a class="publish" href="/WRRL/ADMIN/Codes/ARCHIVE-PAGE/ARCHIVE-PAGE.php">Archives</a></div>
                <div class="logoutbtn"><img  src="/WRRL/IMAGES/log-out.png"><a class="logout" href="http://localhost/WRRL/ADMIN/Codes/LOGIN-PAGE/Login-Page.php">Logout</a></div>
            </div>
        </div>
    </div>
</nav>

<main>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="container">
            <div class="column1">
                <label class="Head1">Strand</label>
                <select class="Strand" name="Strand">
                    <?php
                    $strands = getStrands();
                    foreach ($strands as $strand) {
                        echo "<option value='{$strand['Strand_ID']}'>{$strand['Strand_name']}</option>";
                    }
                    ?>
                </select>

                <label class="Head1" for="pubstat">Publication:</label>
                <select class="pubstat" name="pubstat" id="pubstat" onchange="updateTextarea()">
                    <option disabled selected value="">-</option>
                    <option>Published</option>
                    <option>Not Published</option>
                </select>

                <label class="Head1" for="DOI">DOI</label>
                <textarea class="DOI" placeholder="Enter DOI..." id="DOI" name="DOI" rows="2"  disabled></textarea>

                <script>
                    function updateTextarea() {
                        var statusSelect = document.getElementById("pubstat");
                        var contentTextarea = document.getElementById("DOI");

                        if (statusSelect.value === "Published") {
                            contentTextarea.disabled = false;
                        } else {
                            contentTextarea.disabled = true;
                            contentTextarea.value = "-"; // Set default value to "-"
                        }
                    }
                </script>

                <label class="Head1" for="Title">Title</label>
                <textarea class="Title-input" placeholder="Enter research  title..." id="Title" name="RTitle" rows="3" required></textarea>

                <label class="Head1" for="Authors">Author/s</label>
                <textarea class="Authors" placeholder="List of Authors..." id="Authors" name="Authors" rows="3" required></textarea>

                <label class="Head1" for="Date">Date</label>
                <input class="Date" id="Date" type="date" name="Date" required>

                <label class="Head1">Attach File</label>
                <input class="file" id="file" type="file" name="file" accept="application/pdf" required onchange="updateLabel()"/>
                <label class="Upload-Here" for="file" id="drop-area">Upload file here</label>

                <button class="Publish" id="Publish" type="submit" name="Title">Publish now!</button>
            </div>

            <div class="column2">
                <label class="Head1" for="Abstract">Abstract</label>
                <textarea class="Abstract" placeholder="Enter Abstract..." id="Abstract" name="Abstract" rows="25" required></textarea>

                <label class="Head1" for="Description">Description</label>
                <textarea class="Description" placeholder="Enter Keywords..." id="Description" name="Description" rows="4" required></textarea>

            </div>
        </div>
    </form>
</main>

</body>
</html>
