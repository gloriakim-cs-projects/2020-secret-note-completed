<?php

//only for local host; need to edit for online hosts
$servername = "localhost";
$dBUsername = "root";
$dBPassword = "";
$dBName = "csce3555";

//connect to database
$conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);

//check if the connection is failed
if (!$conn) {
  die("Connection failed: ".mysqli_connect_error());
}
