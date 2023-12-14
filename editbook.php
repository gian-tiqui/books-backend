<?php

require("database.php");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

function editXML($bname, $author, $year, $bid) {
  $xmlFile = simplexml_load_file("books.xml");

  foreach ($xmlFile->book as $book) {
    if ((int)$book['bid'] === (int)$bid) {
      $book->bname = $bname;
      $book->author = $author;
      $book->year = $year;

      $xmlFile->asXML("books.xml");
      return true;
    }
  }

  return false; 
}

function editJSON($bname, $author, $year, $bid) {
  $jsonFile = "books.json";

  $json = json_decode(file_get_contents($jsonFile), true);

  foreach ($json["books"] as &$jBook) {
    if ($jBook["bid"] == $bid) {
      $jBook["bname"] = $bname;
      $jBook["author"] = $author;
      $jBook["year"] = $year;

      file_put_contents($jsonFile, json_encode($json, JSON_PRETTY_PRINT));
      return true;
    }
  }

  return false; 
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $bid = $_POST["bid"];
  $bname = $_POST["bname"];
  $author = $_POST["author"];
  $year = $_POST["year"];

  $updateQuery = "UPDATE tblbooks SET bname = ?, author = ?, year = ? WHERE bid = ?";

  $stmt = $DB->prepare($updateQuery);

  $stmt->bind_param("ssii", $bname, $author, $year, $bid);

  if ($stmt->execute()) {
    if (editXML($bname, $author, $year, $bid) && editJSON($bname, $author, $year, $bid)) {
      echo "success";
    } else {
      echo "failed to update JSON and XML";
    }
  } else {
    echo "failed to update the database";
  }

  $stmt->close();
}

$DB->close();
?>
