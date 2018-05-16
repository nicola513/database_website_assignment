<?php
session_start();
	require_once("oracle-functions.php");
	$conn = connectDB();	
	echo "<b>User name:".$_SESSION['custname']."</b>  | <a href=\"logout.php\">Logout</a></br><hr />";
	$proID=$_SESSION['proID'];
	$proQ=$_POST['quantity'];	
	if($proQ==null){
		die("Please enter Quantity number! <a href=\"buy.php?proID=".$proID."\">Return main page</a></br>");
	}
	
	$sql = 'select decode(count(payID)+1, NULL, 1, count(payID)+1) as MAX_ID from Paymentlist';
			$statement = oci_parse ($conn, $sql);
			oci_execute($statement, OCI_DEFAULT);
			$arr = oci_fetch_assoc($statement);
			$maxID = $arr['MAX_ID'];
			
		 $sql = 'DELETE FROM Paymentlist WHERE payID = :PAYID';
		 $payID="P".$maxID;
		 $statement = oci_parse ($conn, $sql);
		 oci_bind_by_name($statement, ':PAYID', $payID);
		 $e = oci_execute($statement, OCI_COMMIT_ON_SUCCESS);
	
	if (!$e) {
			  die("error");
			}
			oci_free_statement($statement);
	
	$sql="insert into Paymentlist values('".$payID."','".$_SESSION['custid']."','".$proID."',".$proQ.",'waiting for payment')";
	 $statement = oci_parse ($conn, $sql);
	 oci_execute($statement, OCI_COMMIT_ON_SUCCESS);
	 
		echo"Buy success.</br><a href=\"main.php\">Return to main page</a></br>"; 
	
	 
	 oci_free_statement($statement);
	 oci_close($conn);
	 

?>