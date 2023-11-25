<head>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
</head>

<?php
$redis = new Redis();
$redis->connect("127.0.0.1", 6379);


if (!$redis->exists("pet")) {
    include("db.php");
    $conn = new mysqli($host, $username, $password, $dbname);
    $query = "select * from pet";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $myarray = array();
        while ($row = $result->fetch_assoc()) {
            $myarray[] = $row;
        }
        $redis->set("pet", serialize($myarray));
        $source = "From db";
    }
} else {
    $myarray = $redis->get("pet");
    $source = "From redis";
}
echo $source;
?>
<table class='table table-striped'>
    <?php
    echo "<tr>";
    echo "<th scope='col'>";
    echo "<th scope='col'>Name</th>";
    echo "<th scope='col'>Owner</th>";
    echo "<th scope='col'>Birth</th>";
    echo "<th scope='col'>Type</th>";
    echo "<th scope='col'>Gender</th>";
    echo "<th scope='col'>Edit</th>";
    echo "<th scope='col'>Delete</th>";
    echo "<td> <a href='goodedit.php?petid=" . $row["petID"] . "'>Edit</a></td>";
    echo "<td> <a href='javascript:del(" . $row["petID"] . ")'>Delete</a></td>";
    echo "</tr>";
    if ($source == "From db") {
        foreach ($myarray as $row) {
            echo "<tr>";
            echo "<th scope='row'>";
            echo "<td>" . $row['Name'] . "</td>";
            echo "<td>" . $row['Owner'] . "</td>";
            echo "<td>" . $row['Birth'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Gender'] . "</td>";
            echo "</tr>";
        }
    } else {
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
    }
    ?>
    <table>