<?php 
$query = $_GET["query"];
//database query

$servername = "localhost";
$username = "yizhong";
$password = "yizhong";
$dbname = 'yizhong';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully";

// search the query
$query_pair_sql = "select * from translations where english='" . $query . "' or chinese='" . $query . "';";
$query_pair_result = $conn->query($query_pair_sql);
if ($query_pair_result->num_rows > 0) {
    // output data of each row
    include 'result.php';
    include 'content.html';
} else {
    $conn->close();
    include 'error.html';
}

?>

