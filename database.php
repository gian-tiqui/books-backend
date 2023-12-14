<?php

$USERNAME = "root";
$PASSWORD = "hakdog2001";
$HOSTNAME = "localhost";
$DATABASE = "books";

$DB = new mysqli($HOSTNAME, $USERNAME, $PASSWORD, $DATABASE);

$BOOKS_TABLE = "tblbooks";
$USERS_TABLE = "tblusers";
?>