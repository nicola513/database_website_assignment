<?php
require_once("oracle-functions.php");
$conn = connectDB();

$imageFiles=$_FILES['upload']['tmp_name'];
$proName=$_POST['proName'];
$price=$_POST['price'];
$topic=$_POST['topic'];
$pet=$_POST['pet'];
$proDesc=$_POST['proDesc'];

 $query = 'select decode(max(proID)+1, NULL, 1, max(proID)+1) as MAX_ID from Product';
    $statement = oci_parse ($conn, $query);
    oci_execute($statement, OCI_DEFAULT);
    $arr = oci_fetch_assoc($statement);
    $proID = $arr['MAX_ID'];
	
 $query = 'DELETE FROM Product WHERE proID = :PROID';
 $statement = oci_parse ($conn, $query);
 oci_bind_by_name($statement, ':PROID', $proID);
 $e = oci_execute($statement, OCI_COMMIT_ON_SUCCESS);

    if (!$e) {
      die;
    }
	
	oci_free_statement($statement);
	
	$lob = oci_new_descriptor($conn, OCI_D_LOB);
    $statement = oci_parse($conn, 'INSERT INTO Product (proID,proDescription,proName,price,topic,pet,image) '
    .'VALUES(:PROID,'.$proDesc.','.$proName.','.$price.','.$topic.','.$pet.' ,EMPTY_BLOB()) RETURNING image INTO :IMAGE');

    oci_bind_by_name($statement, ':PROID', $proID);
    oci_bind_by_name($statement, ':IMAGE', $lob, -1, OCI_B_BLOB);
    oci_execute($statement, OCI_DEFAULT);
	
	 if ($lob->savefile($imageFiles)) {
      oci_commit($conn);
	   echo "BLOB uploaded";
    }else {
      echo "Couldn't upload Blob\n";
    }
	$lob->free();
    oci_free_statement($statement);
	
	$query = 'SELECT image FROM Product WHERE ProID = :MYBLOBID';

    $statement = oci_parse ($conn, $query);
    oci_bind_by_name($statement, ':MYBLOBID', $myblobid);
    oci_execute($statement, OCI_DEFAULT);
    $arr = oci_fetch_assoc($statement);
    $result = $arr['image']->load();

    header("Content-type: image/JPEG");
    echo $result;

    oci_free_statement($statement);
    oci_close($conn); // log off
	
	
	
?>