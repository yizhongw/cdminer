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
    $query_pair_sql = "select * from translations where english like'" . $query . "%' or chinese like '" . $query . "%';";
    $query_pair_result = $conn->query($query_pair_sql);
    if ($query_pair_result->num_rows > 0){
        include 'result.php';
        include 'error.html';
    } else {
        $success = FALSE;
        for ($i=1; $i < min(10, strlen($query)); $i++) { 
            $prefix = substr($query, 0, -$i);
            $query_pair_sql = "select * from translations where english like'" . $prefix . "%' or chinese like '" . $prefix . "%';";
            $query_pair_result = $conn->query($query_pair_sql);
            if ($query_pair_result->num_rows > 0){
                $success = TRUE;
                include 'result.php';
                include 'error.html';
                break;
            }
        }
        if (! $success) {
            include 'pure_error.html';
        }
    }
}

?>

