<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<?php
include("menu.php");
include("db.php");
session_start();
$redis = new Redis();
$redis->connect("127.0.0.1", 6379);

if (isset($_SESSION["login_user"])) {
} else {
    header("location: goodlogin.php");
}


$conn = new mysqli($host, $username, $password, $dbname);
if (isset($_GET['petid'])) {
    $petid = $_GET['petid'];
    $sql = "SELECT * FROM pet WHERE petID = $petid";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        echo "Pet not found.";
        exit;
    }
} else {
    echo "Pet ID not provided.";
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $owner = $_POST['owner'];
    $type = $_POST['type'];
    $gender = $_POST['gender'];
    $birth = $_POST['birth'];

    $updateSql = $conn->prepare("UPDATE pet SET Name = ?, Owner = ?, Type = ?, Gender = ?, Birth = ? WHERE petID = ?");
    $updateSql->bind_param('sssssi', $name, $owner, $type, $gender, $birth, $petid);
    $redis->del($name);
    if ($updateSql->execute() == true) {
        echo "Data updated";
    } else {
        echo "Error updating data: " . mysqli_error($conn);
    }
}

$conn->close();
?>

<style>
    body {
        margin: 20px;
    }

    .container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .body {
        font-family: Arial, sans-serif;
        margin: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    h1 {
        color: #007bff;
    }

    form {
        margin-top: 20px;
        width: 100%;
        max-width: 400px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        border-radius: 8px;
    }

    table {
        width: 100%;
        border: none;
    }

    td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: center;
        justify-content: center;
    }

    input,
    select {
        width: 100%;
        padding: 8px;
        margin: 4px 0;
        display: inline-block;
        border: 1px solid #ccc;
        box-sizing: border-box;
        border-radius: 5px;
    }

    button {
        background-color: #007bff;
        color: #fff;
        margin-top: 5px;
        padding: 10px 15px;
        border: none;
        cursor: pointer;
        border-radius: 4px;
        width: 100%;
        box-sizing: border-box;
    }

    button:hover {
        background-color: #0056b3;
    }

    font[color="red"] {
        color: red;
    }

    a {
        margin-left: 50px;
        color: #007bff;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }
</style>
<html>

<head>
    <title>Edit Pet</title>
</head>

<body>
    <div class="container">
        <h2>Edit Pet</h2>
        <div class="body">
            <form action="goodedit.php?petid=<?php echo $petid; ?>" method="post">
                <table>
                    <tr>
                        <td>Name:</td>
                        <td><input type="text" name="name" value="<?php echo $row['Name']; ?>"></td>
                    </tr>
                    <tr>
                        <td>Owner:</td>
                        <td><input type="text" name="owner" value="<?php echo $row['Owner']; ?>"></td>
                    </tr>
                    <tr>
                        <td>Type:</td>
                        <td><input type="text" name="type" value="<?php echo $row['Type']; ?>"></td>
                    </tr>
                    <tr>
                        <td>Gender:</td>
                        <td><input type="text" name="gender" value="<?php echo $row['Gender']; ?>"></td>
                    </tr>
                    <tr>
                        <td>Birth:</td>
                        <td><input type="date" name="birth" value="<?php echo $row['Birth']; ?>"></td>
                    </tr>
                </table>
                <input type="submit" value="Save">
            </form>
        </div>
    </div>
</body>

</html>