<style>
.con {
  color: White;
  display: flex;
  flex-direction: column;
  text-align: center;
  align-items: center;
  justify-content: center;
  font-family: sans-serif;
  position: absolute;
  bottom: 5px;
  right: 30px;  
}
label {
  font-size: 15px;
  font-weight: bold;
  margin: 0;
}
a {
  display: flex;
  flex-direction: column;
  Color: white; 
  text-decoration: none;
}
.ID{
  margin: 5px;
  font-size: 13px;

}
.ID:hover{
  color: #f1c40f;
  text-decoration: underline;
}
.Profile {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  margin-right: 10px;

}

</style>

<div class="con">
    <?php
    session_start();
    include("getDB.php");

    if (!isset($_SESSION["fac_accounts"])) {
        header("Location: http://localhost/WRRL/FAC/Codes/LOGIN-PAGE/LOGIN-PAGE.php");
        exit();
    }

    $ID_number = $_SESSION["fac_accounts"]["ID_number"];
    $userInfo = getUserInfo($ID_number);

    if ($userInfo) {
        $userprofileBlob = $userInfo["Profile"];
        $userName = $userInfo["Firstname"];
        $userID = $userInfo["ID_number"];
    ?>
    <img class="Profile" src="data:image/jpeg;base64,<?php echo base64_encode($userprofileBlob); ?>">
    <a href="http://localhost/WRRL/FAC/Codes/Profile/Profile.php">
        <Label><?php echo $userName; ?></Label>
        <p class="ID"><?php echo $userID; ?></p>
    </a>
    <?php
    }
    ?>
</div>


</div>

