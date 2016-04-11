<?php 
$row = $query_pair_result->fetch_assoc();
$en_word = $row["english"];
$ch_word = $row["chinese"];

$query_similarity_sql = "select * from similarity where english='" . $en_word . "' and chinese='" . $ch_word ."';";
$query_similarity_result = $conn->query($query_similarity_sql);
if ($query_similarity_result->num_rows > 0) {
    // output data of each row
    $row = $query_similarity_result->fetch_assoc();
    $diff_rank = $row["rank"];
} else {
    $diff_rank = 5400;
}
$diff_percent = $diff_rank / 5400;

$en_neighbors_string = "[";
$query_en_neighbor_sql = "select * from en_neighbor where center='" . $en_word . "';";
$query_en_neighbor_result = $conn->query($query_en_neighbor_sql);
if ($query_en_neighbor_result->num_rows > 0) {
    while($row = $query_en_neighbor_result->fetch_assoc()) {
        $new_element = "{name:'" . $row["neighbor"] . "',similarity:" . $row["similarity"] . "}";
        $en_neighbors_string = $en_neighbors_string . $new_element . ",";
    }
}
$en_neighbors_string = $en_neighbors_string . "]";
// echo "$en_neighbors_string";

$ch_neighbors_string = "[";
$query_ch_neighbor_sql = "select * from ch_neighbor where center='" . $ch_word . "';";
$query_ch_neighbor_result = $conn->query($query_ch_neighbor_sql);
if ($query_ch_neighbor_result->num_rows > 0) {
    while($row = $query_ch_neighbor_result->fetch_assoc()) {
        $new_element = "{name:'" . $row["neighbor"] . "',similarity:" . $row["similarity"] . "}";
        $ch_neighbors_string = $ch_neighbors_string . $new_element . ",";
    }
}
$ch_neighbors_string = $ch_neighbors_string . "]";
// echo "$ch_neighbors_string";
$conn->close();


//bing image search
$accountKey = 'GZBFwBsBoLyxOy2dhDf4pkBd5XrJZJFZWs13QFdBnTg'; 
$ServiceRootURL = 'https://api.datamarket.azure.com/Bing/Search/'; 
$WebSearchURL = $ServiceRootURL . 'Image?$format=json&$top=4&Query='; 
$context = stream_context_create(array( 'http' => array( 'request_fulluri' => true, 'header' => "Authorization: Basic " . base64_encode($accountKey . ":" . $accountKey) ) )); 

// Encode the query and the single quotation marks that must surround it. 
$request = $WebSearchURL . urlencode("'{$en_word}'"); 
$response = file_get_contents($request, 0, $context); 
$jsonobj = json_decode($response); 
$en_images = array();

foreach($jsonobj->d->results as $value) { 
    array_push($en_images, $value->Thumbnail->MediaUrl); 
}

$request = $WebSearchURL . urlencode("'{$ch_word}'"); 
$response = file_get_contents($request, 0, $context); 
$jsonobj = json_decode($response); 
$ch_images = array();
foreach($jsonobj->d->results as $value) { 
    array_push($ch_images, $value->Thumbnail->MediaUrl); 
}

?>