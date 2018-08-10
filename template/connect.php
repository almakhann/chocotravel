<?php
  $vname = null;
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "choco";
  $conn = new mysqli($servername, $username, $password, $dbname);

  if($conn -> connect_error){ 
    die("connection failed");
  }


?>