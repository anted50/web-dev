<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<?php
include("menu.php");
//connection
include("db.php");
session_start();

if (isset($_SESSION["login_user"])) {
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("error:" . $conn->connect_error);
    }
    if (isset($_POST["name"])) {
        $name = htmlspecialchars($_POST["name"]);
        $owner = htmlspecialchars($_POST["owner"]);
        $birth = htmlspecialchars($_POST["birth"]);
        $gender = htmlspecialchars($_POST["gender"]);
        $type = htmlspecialchars($_POST["type"]);

        $sql = $conn->prepare("INSERT into pet (name, owner, gender, type, birth)
            VALUES (?,?,?,?,?)");
        mysqli_stmt_bind_param($sql, 'sssss', $name, $owner, $gender, $type, $birth);

        if ($sql->execute() == True) {
            $msg = "Pet registered successfully";
        } else {
            die("Error:" . $conn->error);
        }
        $conn->close();
    }
} else {
    header("location: goodlogin.php");
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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
</head>

<body>
    <div class="container">
        <h1>Pet Registration</h1>
        <div class="body">
            <form action="goodregister.php" method="POST">
                <table>
                    <tr>
                        <td>Name</td>
                        <td colspan="2"><input type="text" name="name"></td>
                    </tr>
                    <tr>
                        <td>Owner</td>
                        <td colspan="2"><input type="text" name="owner"></td>
                    </tr>
                    <tr>
                        <td>Date of Birth</td>
                        <td colspan="2"><input type="date" name="birth"></td>
                    </tr>
                    <tr>
                        <td>Gender</td>
                        <td><input type="radio" name="gender" value="male">Male</td>
                        <td><input type="radio" name="gender" value="female">Female</td>
                    </tr>
                    <tr>
                        <td>Type</td>
                        <td colspan="2">
                            <select name="type">
                                <option value="dog">Dog</option>
                                <option value="cat">Cat</option>
                                <option value="snake">Snake</option>
                                <option value="bird">Bird</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <button class="btn-primary" type="submit" value="submit">Submit</button>
            </form>
        </div>
    </div>
    <?php
    if (isset($msg)) {
        echo "<font color='red'>" . $msg . "</font>";
    }
    ?>

</body>