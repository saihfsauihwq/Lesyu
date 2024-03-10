<?php
session_start();
include("getDB.php");
include("Displayinfo.php");

if (!isset($_SESSION["admin_accounts"])) {
    header("Location: Login-Page.php");
    exit();
}

$userId = $_SESSION["admin_accounts"]["USER-ID"];
$adminName = getAdminName($userId);

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Retrieve the search term from the form
    $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

    // If a search term is provided, filter the user data
    if (!empty($searchTerm)) {
        $userData = filterUserData($searchTerm);
    } else {
        // If no search term is provided, get all user data
        $userData = getUserData();
    }
} else {
    // If the form is not submitted, get all user data
    $userData = getUserData();
}

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
    <link rel="Stylesheet" href="General.css">
    <link rel="Stylesheet" href="Header.css">
    <link rel="Stylesheet" href="main.css">
    <link rel="Stylesheet" href="Searchbar.css">
    <link rel="Stylesheet" href="nav.css">
    <link rel="Stylesheet" href="Page.css">
    <link rel="Icon" href="/WRRL/IMAGES/favicon.png">
    <script>
    function submitForm(element) {
    var form = element.closest('form');
    form.submit();
    console.log("Form submitted");
    }
    </script>
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
                <div><img src="/WRRL/IMAGES/file-invoice.png"><a class="publish" href="http://localhost/WRRL/ADMIN/Codes/PUBLISHLIST/Publishlist.php">Uploads</a></div>
                <div><img src="/WRRL/IMAGES/file-upload.png"><a class="publish" href="/WRRL/ADMIN/Codes/PUBLISH-PAGE/PUBLISH-PAGE.php">Upload new paper</a></div>
                <div><img src="/WRRL/IMAGES/user.png"><a style="color: rgb(164,0,1); text-decoration: underline;" class="publish" href="/WRRL/ADMIN/Codes/USERLIST/USERLIST.php">List of Accounts</a></div>
                <div><img src="/WRRL/IMAGES/folders.png"><a class="publish" href="/WRRL/ADMIN/Codes/ARCHIVE-PAGE/ARCHIVE-PAGE.php">Archives</a></div>
                <div class="logoutbtn"><img  src="/WRRL/IMAGES/log-out.png"><a class="logout" href="http://localhost/WRRL/ADMIN/Codes/LOGIN-PAGE/Login-Page.php">Logout</a></div>
            </div>
        </div>
    </div>
</nav>

<main>
    <div class="main-container">
        <div class="table">

        <h1 class="Head">List of Account registered
            <a style="background-color: rgb(164,0,1); color: white;" href="http://localhost/WRRL/ADMIN/Codes/USERLIST/USERLIST.php" class="button">STUDENT</a>
            <a href="http://localhost/WRRL/ADMIN/Codes/FACULTYLIST/FACULTYLIST.php" class="button">FACULTY</a>
        </h1>  

        <form action="USERLIST.php" method="get">
            <div class="Searchbar">
            <input class="type" type="text" name="search" placeholder="Search ID-Number" class="search" value="<?php echo htmlspecialchars(isset($_GET['search']) ? $_GET['search'] : ''); ?>">
            <button class="search" type="submit" class="search-button">Search</button>
            </div>
        </form>

            <table>
                <tr>
                    <th class="ID-number">ID-number</th>
                    <th class="Fname">Name</th>
                    <th class="Strand">Strand</th>
                    <th class="Gradesec">Grade & Section</th>
                    <th class="Status">Status</th>
                    <th class="Date">Date Registered</th>
                    <th class="Accstat">Account Status</th>
                    
                </tr>
                <?php
                // Loop through the fetched data and populate the table rows
                foreach ($userData as $row) {
                    echo "<tr>";
                    echo "<td class='ID-number'>" . $row['ID_number'] . "</td>";
                    echo "<td class='Fname'><a href='http://localhost/WRRL/ADMIN/Codes/userprofile/profile.php?ID=" . $row['ID_number'] . "'>";
                    echo "<img src='data:image/jpeg;base64," . base64_encode($row['Profile']) . "'>" . $row['Firstname'] . ' ' . $row['Lastname'];
                    echo "</a></td>";
                    echo "<td class='Strand'>" . $row['Strand'] . "</td>";
                    echo "<td class='Gradesec'>" . $row['Grade_Section'] . "</td>";
                    echo "<td class='Status'>" . $row['EStatus'] . "</td>";
                    echo "<td class='Date'>" . $row['Date_Registered'] . "</td>";
                    echo "<td class='Accstat'>";
                    echo "<form method='post' action='' id='updateForm_" . $row['ID_number'] . "'>";
                    echo "<input type='hidden' name='ID_num' value='" . $row['ID_number'] . "'>";
                    echo "<select name='Account_stat' onchange='submitForm(this)'>";
                    echo "<option value='Active'" . ($row['Account_status'] == 'Active' ? ' selected' : '') . ">Active</option>";
                    echo "<option value='Inactive'" . ($row['Account_status'] == 'Inactive' ? ' selected' : '') . ">Inactive</option>";
                    echo "</select>";
                    echo "</form>";                  
                    echo "</td>";
                    echo "</tr>";
                }
                ?>

                <script>
                function submitForm(element) {
                    var form = element.closest('form');
                    form.submit();
                    console.log("Form submitted");
                }
                </script>
            </table>
            <?php if (empty($userData)): ?>
            <p class="error">No User Found</p>
        <?php endif; ?>
        </div>
    </div>
</main>
</body>
</html>
