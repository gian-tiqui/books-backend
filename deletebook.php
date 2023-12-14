<?php
require("database.php");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

function deleteXML($id) {
  $xmlFile = simplexml_load_file("books.xml");
  $dBook = null;

  foreach ($xmlFile->book as $book) {
    if ((int)$book['bid'] === (int)$id) {
      $dBook = $book;
      break;
    }
  }

  if ($dBook) {
    $dom = dom_import_simplexml($dBook);
    $dom->parentNode->removeChild($dom);

    if ($xmlFile->asXML("books.xml")) {
      return true;
    } else {
      return false;
    }
  }

  return false;
}

function deleteJSON($id) {
  $jsonFile = "books.json";
  $json = json_decode(file_get_contents($jsonFile), true);

  foreach ($json["books"] as $key => $jBook) {
    if ($jBook["bid"] == $id) {
      unset($json["books"][$key]);
      break;
    }
  }

  $json["books"] = array_values($json["books"]);

  if (file_put_contents($jsonFile, json_encode($json, JSON_PRETTY_PRINT))) {
    return true;
  } else {
    return false;
  }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $bid = $_POST["bid"];

  $deleteQuery = "DELETE FROM tblbooks WHERE bid = ?";

  $stmt = $DB->prepare($deleteQuery);

  $stmt->bind_param("i", $bid);

  if ($stmt->execute()) {
    if (deleteXML($bid) && deleteJSON($bid)) {
      echo "success";
    } else {
      echo "failed to update XML/JSON";
    }
  } else {
    echo "failed to delete from the database";
  }

  $stmt->close();
}

$DB->close();
?>
