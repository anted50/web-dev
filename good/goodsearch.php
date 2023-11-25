<?php
include("menu.php");
//connection
include("db.php");
session_start();
if (isset($_SESSION["login_user"])) {
} else {
    header("location: goodlogin.php");
}
include("db.php");
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("error:" . $conn->connect_error);
}

$redis = new Redis();
$redis->connect("127.0.0.1", 6379);
$source = "";
if (isset($_POST['name'])) {
    $name = $_POST['name'];
    if (!$redis->exists($name)) {
        $sql = "Select * from pet where Name = '$name%'";
        $nameWithWildcard = $name . "%";

        $sql = "SELECT * FROM pet WHERE Name LIKE ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $nameWithWildcard);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
        }
        if ($result->num_rows > 0) {
            $myarray = array();
            while ($row = $result->fetch_assoc()) {
                $myarray[] = $row;
            }
            $redis->set($name, serialize($myarray), 600);
            $source = "From mySQL";
        }
    } else {
        $myarray = $redis->get($name);
        $source = "From Redis";
    }
}
?>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
        margin: 20px;
    }

    h1 {
        color: #007bff;
        text-align: center;
    }

    table {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    th,
    td {
        padding: 12px;
        text-align: left;
        border: 1px solid #ddd;
    }

    th {
        background-color: #007bff;
        color: black;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
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

<script>
    function del(a) {
        let text = "Do you want to delete?";
        if (confirm(text)) {
            window.location = `gooddelete.php?id=${a}`;
        }
    }
</script>


<h1>Search</h1>

<body>
    <form action="goodsearch.php" method="post">
        Enter a PET name: <input type="text" name="name">
        <button class="btn-primary" type="submit" value="submit">Submit</button>
        <table style="width" class="table table-striped">
            <?php
            echo "<h4>" . $source . "</h4>";
            echo "<tr>";
            echo "<th scope='col'>";
            echo "<th scope='col'>Name</th>";
            echo "<th scope='col'>Owner</th>";
            echo "<th scope='col'>Birth</th>";
            echo "<th scope='col'>Type</th>";
            echo "<th scope='col'>Gender</th>";
            echo "<th scope='col'>Edit</th>";
            echo "<th scope='col'>Delete</th>";
            echo "</tr>";
            if ($source == "From mySQL") {
                foreach ($myarray as $row) {
                    echo "<tr>";
                    echo "<th scope='row'>";
                    echo "<td>" . $row['Name'] . "</td>";
                    echo "<td>" . $row['Owner'] . "</td>";
                    echo "<td>" . $row['Birth'] . "</td>";
                    echo "<td>" . $row['Type'] . "</td>";
                    echo "<td>" . $row['Gender'] . "</td>";
                    echo "<td> <a href='goodedit.php?petid=" . $row["petID"] . "'>Edit</a></td>";
                    echo "<td> <a href='javascript:del(" . $row["petID"] . ")'>Delete</a></td>";
                    echo "</tr>";
                }
            } else if ($source == "From Redis") {
                $myarray = unserialize($myarray);
                foreach ($myarray as $row) {
                    echo "<tr>";
                    echo "<th scope='row'>";
                    echo "<td>" . $row['Name'] . "</td>";
                    echo "<td>" . $row['Owner'] . "</td>";
                    echo "<td>" . $row['Birth'] . "</td>";
                    echo "<td>" . $row['Type'] . "</td>";
                    echo "<td>" . $row['Gender'] . "</td>";
                    echo "<td> <a href='goodedit.php?petid=" . $row["petID"] . "'>Edit</a></td>";
                    echo "<td> <a href='javascript:del(" . $row["petID"] . ")'>Delete</a></td>";
                    echo "</tr>";
                }
            } else if($source != "") {
                echo "Result not found.";
            }
            ?>
        </table>
    </form>
</body>