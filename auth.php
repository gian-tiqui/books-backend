<?php

require("database.php");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = $_POST["email"];
  $password = $_POST["password"];

  $user = array();

  $query = "SELECT * FROM tblusers WHERE email LIKE ? AND password LIKE ?";
  $stmt = $DB->prepare($query);

  $stmt->bind_param("ss", $email, $password);

  if ($stmt->execute()) {
      $result = $stmt->get_result();
      while ($row = $result->fetch_assoc()) {
          $user[] = $row;
      }

      echo json_encode($user);  
  } else {
      echo "failed";
  }

  $stmt->close();
}


$DB->close();  
?>


?>