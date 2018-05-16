<?php

require_once("oracle-functions.php");
$conn = connectDB();

$proID = $_GET['proID'];

$query = "SELECT * FROM Product WHERE PROID= ".$proID;

$statement = oci_parse ($conn, $query);
oci_execute($statement, OCI_DEFAULT);
$row = oci_fetch_assoc($statement);
  header("Content-type: image/JPG");
  echo $row['BLOBDATA']->load();
  oci_free_statement($statement);
  oci_close($conn);

?>