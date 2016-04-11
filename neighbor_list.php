<?php 
$query = $_GET["query"];
$lang = $_GET["lang"];
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
if ($lang == 'chinese' or $lang == 'Chinese') {
    $query_neighbor_sql = "select * from ch_full_neighbor where center='" . $query . "' order by similarity desc;";
} else{
    $query_neighbor_sql = "select * from en_full_neighbor where center='" . $query . "' order by similarity desc;";
}

$list = "";

$query_neighbor_result = $conn->query($query_neighbor_sql);
$cnt = 0;
if ($query_neighbor_result->num_rows > 0) {
    while($row = $query_neighbor_result->fetch_assoc()) {
        $cnt += 1;
        $new_element = "<tr><th scope='row'>" . $cnt . "</th>" . "<td>" . $row["center"] . "</td>" . "<td>" . $row["neighbor"] . "</td>" . "<td>" . $row["similarity"] . "</td></tr>";
        $list = $list . $new_element;
    }
}

$conn->close();

?>

<!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8">

    <title>CDMiner</title>

    <link rel='shortcut icon' href='favicon.png' type='image/x-icon'/ >

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>

    <script src="http://apps.bdimg.com/libs/bootstrap/3.3.4/js/bootstrap.min.js"></script>

    <style type="text/css">
        .top1 {
            height:120px;
            width:120px;
            background-color:#FFFFFF;
            margin-top:0px;
            overflow: hidden;
        }
        .accurate-h {
            margin-top: 0px;
            margin-bottom: 0px;
        }
        .circle {
          stroke: #fff;
          stroke-width: 1.5px;
        }

        .link {
          stroke: #999;
          stroke-opacity: .6;
        }
    </style>

</head>

<body>
<div class="container-fluid">
        <table class="table">
          <thead class="thead-inverse">
            <tr>
              <th>#</th>
              <th>center word</th>
              <th>neighbors</th>
              <th>similarity</th>
            </tr>
          </thead>
          <tbody>
            <?php echo $list?>
          </tbody>
        </table>
</div>
</body>
</html>