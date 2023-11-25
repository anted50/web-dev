<?php
include("menu.php");
include("db.php");
session_start();
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("error:" . $conn->connect_error);
}


if (isset($_POST["email"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email = htmlspecialchars($email);

        // $email = $mysqli -> real_escape_string($email);
        // $sql = $mysqli->prepare("SELECT * FROM employee WHERE email = '$email'");
        // $sql->bind_param("s", $email);

        $sql = "SELECT * FROM employee WHERE email = '$email'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        if ($row["email"] == $email) {
            if ($row["pass"] == $password) {
                $_SESSION['login_user'] = $row["name"];
                header("location: list.php");
            } else {
                echo "Password incorrect";
            } 
            // echo "invalid account";
        }
    } else {
        echo "<br><p style='color: red;'>Invalid email<p>";
    }


}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="goodlogin.php" method="POST">
        <table>
            <tr>
                <td>E-Mail:</td>
                <td><input type="textfield" name="email" required></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><input type="password" name="password" required></td>
            </tr>
            <tr>
                <td><button class="btn btn-primary" type="submit" value="submit">Log In</button></td>
            </tr>
        </table>
    </form>
</body>

</html>