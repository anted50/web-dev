<!-- $petid = mysqli_real_escape_string($conn,$petid); -->
<?php
include("db.php");
session_start();
$conn = new mysqli($host, $username, $password, $dbname);
if (isset($_SESSION["login_user"])) {
    if ($conn->connect_error) {
        die("error:" . $conn->connect_error);
    }

    $deleteSql = $conn->prepare("DELETE FROM pet WHERE petID=?");
    $petid = htmlspecialchars($_GET["id"]);
    $deleteSql->bind_param("s", $petid);
    $deleteSql->execute();
    header("location: list.php");

    $conn->close();
} else {
    header("location: goodlogin.php");
}


?>