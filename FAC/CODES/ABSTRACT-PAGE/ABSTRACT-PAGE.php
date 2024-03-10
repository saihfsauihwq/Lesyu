  <?php
  include 'dbconnect.php';
  include 'DisplayAbstract.php';

  if (isset($_GET['uid']) && $_GET['uid'] > 0) {
    $uid = $_GET['uid'];

    $researchDetails = getResearchPaperDetails($fileDb, $uid);

    $title = $researchDetails['title'];
    $abstract = $researchDetails['abstract'];
    $filespath = $researchDetails['filespath'];
    $Description = $researchDetails['Description'];
} else {
    $title = $abstract = $filespath = "Invalid request";
}
  ?>
  <!Doctype html>
    <html lang="en">
      <head>
        <meta charset= UTF-8>
        <meta name="Author" content="Jhon llyod Navarro">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Research Papers repository For Liceo">
        <meta name="keywords" content="Research, Repository, Liceo, University, search bar">
        <title>Web-Based Research Repository</title>
        <link rel="Stylesheet" href="General.css">
        <link rel="Stylesheet" href="Header.css">
        <link rel="Stylesheet" href="main.css">
        <link rel="Stylesheet" href="Searchbar.css">
        <link rel="Stylesheet" href="Nav.css">
        <link rel="Stylesheet" href="popup.css">
        <link rel="Icon" href="/WRRL/IMAGES/favicon.png">
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

        <div class="ABSTRACT-CONTENT">
            <div class="Abstract-Container">
                <h2 class="Research-Title"><?php echo $title; ?></h2>
                <div class="Info">
                    <h2 class="Abstract-label">Abstract</h2>
                    <p class="Abstract"><?php echo $abstract; ?></p>

                    <h2 class="Abstract-label">Description</h2>
                    <p class="Abstract"><?php echo $Description; ?></p>

                    <h1 class="Abstract-label">Date</h1>
                    <p class="Abstract"><?php echo $researchDetails['date']; ?></p>

                    <h2 class="Author">Author</h2>
                  <?php
                    $authorsArray = explode(',', $researchDetails['authors']);
                    
                    foreach ($authorsArray as $author) {
                        echo "<p class='List-of-Authors'>$author</p>";
                    }
                  ?>

                  <h1 class="Date-header">DOI</h1>
                  <a class="link" href="<?php echo $researchDetails['DOI']; ?>" target="_blank"><?php echo $researchDetails['DOI']; ?></a>
                </div>

                <button class="View-button" onclick="viewFile('<?php echo $uid; ?>')">View</button>
            </div>
        </div>
    </main>

    <script class="view">
    function viewFile(uid) {
    window.location.href = "view.php?uid=" + uid; }
    </script>
</body>

</html>