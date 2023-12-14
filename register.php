<?php
require("database.php");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");


if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = $_POST["email"];
  $password = $_POST["password"];
  $username = $_POST["username"];

  $query = "INSERT INTO tblusers(email, password, username) VALUES (?, ?, ?)";

  $stmt = $DB->prepare($query);

  if ($stmt) {
    $stmt->bind_param("sss", $email, $password, $username);

    $result = $stmt->execute();

    if ($result) {
      echo "success";
    } else {
      echo "failed: " . $stmt->error;
    }

    $stmt->close();
  }

} else {
  echo "Invalid request method";
}

$DB->close();
?>
