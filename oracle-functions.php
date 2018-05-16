<?php  
function connectDB() {
  $userID = 's1153552';
  $password = '11535522';

  $db =
    "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP) " .
    "(HOST = oracleacademy.ouhk.edu.hk)(PORT=8998)) " .
    "(CONNECT_DATA=(SERVER=DEDICATED) " .
    "(SID=db1011)))";
  $conn = oci_connect($userID, $password, $db);
  if (!$conn) {
    $e = oci_error();
    echo $e['message']; //print error message
  }
  return $conn;
} 
?>