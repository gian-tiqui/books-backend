<?php

require("database.php");

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

if ($_SERVER["REQUEST_METHOD"] === "GET") {
  
  $books = array();

  $query = "SELECT * FROM tblbooks";

  $stmt = $DB->prepare($query);

  if ($stmt->execute()) {
    $result = $stmt->get_result(); 

    while ($row = $result->fetch_assoc()) {
      $books[] = $row;
    }

    echo json_encode($books);
  } else {
    echo "failed";
  }
}

$DB->close();  
?>
