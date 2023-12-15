<?php
require("database.php");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

if ($_SERVER["REQUEST_METHOD"] === "GET") {
  
  $messages = array();

  $query = "SELECT * FROM tblmessages";

  $stmt = $DB->prepare($query);

  if ($stmt->execute()) {
    $result = $stmt->get_result(); 

    while ($row = $result->fetch_assoc()) {
      $messages[] = $row;
    }

    echo json_encode($messages);
  } else {
    echo "failed";
  }
}

$DB->close();  
?>
