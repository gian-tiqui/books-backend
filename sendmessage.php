<?php
require("database.php");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = $_POST["name"];
  $email = $_POST["email"];
  $message = $_POST["message"]; 

  $query = "INSERT INTO tblmessages(name, email, message) VALUES (?, ?, ?)";

  $stmt = $DB->prepare($query);

  if ($stmt) {
    $stmt->bind_param("sss", $name, $email, $message);

    $result = $stmt->execute();

    if ($result) {
      echo "success";
    } else {
      echo "failed";
    }
  } else {
    echo "failed: " . $DB->error;
  }
} else {
  echo "Invalid request method";
}

$DB->close();
?>
