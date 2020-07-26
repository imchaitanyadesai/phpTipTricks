<!DOCTYPE html>
<html>
<body>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "placesdistance";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, name, lat, lng, ( 3959 * acos( cos( radians(-33.737885) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(151.235260) ) + sin( radians(-33.737885) ) * sin( radians( lat ) ) ) ) AS distance FROM markers HAVING distance < 25 ORDER BY distance LIMIT 0 , 20;";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<br> id: ". $row["id"]. " - Name: ". $row["name"]. " - Latitdue" . $row["lat"] . " - Longitude ".$row['lng']." - Distance ".$row['distance']."<br>";
    }
} else {
    echo "0 results";
}

$conn->close();
?>

</body>
</html>