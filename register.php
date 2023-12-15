<?php

require("database.php");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = $_POST["email"];
  $password = $_POST["password"];
  $username = $_POST["username"];

  $checkQuery = "SELECT * FROM tblusers WHERE email = ?";
  $checkStmt = $DB->prepare($checkQuery);

  if ($checkStmt) {
    $checkStmt->bind_param("s", $email);

    if ($checkStmt->execute()) {
      $checkResult = $checkStmt->get_result();

      if ($checkResult->num_rows > 0) {
        $checkStmt->close();
        echo "Email exists";
        exit();  
      }
    }
    $checkStmt->close();
  }

  $query = "INSERT INTO tblusers(email, password, username) VALUES (?, ?, ?)";
  $stmt = $DB->prepare($query);

  if ($stmt) {
    $stmt->bind_param("sss", $email, $password, $username);

    if ($stmt->execute()) {
      echo "success";
    } else {
      echo "failed: " . $stmt->error;
    }

    $stmt->close();
  } else {
    echo "failed: " . $DB->error;
  }

} else {
  echo "Invalid request method";
}

$DB->close();
?>
