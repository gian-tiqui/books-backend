<?php

require("database.php");

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

function addJSON($id, $bname, $author, $year) {
  $jsonFile = "books.json";
  $json = json_decode(file_get_contents($jsonFile), true);

  $book = [
    "bid" => $id,
    "bname" => $bname,
    "author" => $author,
    "year" => $year
  ];

  $json['books'][] = $book;
  file_put_contents($jsonFile, json_encode($json));
}

function addXML($id, $bname, $author, $year) {
  $xmlFile = simplexml_load_file('books.xml');

  $book = $xmlFile->addChild("book");
  $book->addAttribute("bid", $id);
  $book->addChild("bname", $bname);
  $book->addChild("author", $author);
  $book->addChild("year", $year);

  $xmlFile->asXML("books.xml");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $bname = $_POST["bname"];
  $author = $_POST["author"];
  $year = $_POST["year"];

  $insertQuery = "INSERT INTO tblbooks(bname, author, year) VALUES (?, ?, ?)";

  $insertStmt = $DB->prepare($insertQuery);

  if ($insertStmt) {
    $insertStmt->bind_param("ssi", $bname, $author, $year);

    $insertResult = $insertStmt->execute();

    if ($insertResult) {
      $maxID;

      $getQuery = "SELECT MAX(bid) AS mID FROM tblbooks";

      $getStmt = $DB->prepare($getQuery);

      if ($getStmt->execute()) {
        $getResult = $getStmt->get_result();

        $maxID = $getResult->fetch_assoc()["mID"];

        addJSON($maxID, $bname, $author, $year);
        addXML($maxID, $bname, $author, $year);
      }

      echo "success";
    } else {
      echo "failed: " . $insertStmt->error;
    }

    $insertStmt->close();
  }

} else {
  echo "Invalid request method";
}

$DB->close();
?>
