<html>
<head><title>Payment Received</title></head>
<body>
<?php
session_start();
		require_once("oracle-functions.php");
		$conn = connectDB();	
			
		echo "<b>User name:".$_SESSION['custname']."</b>  | <a href=\"logout.php\">Logout</a></br><hr />";
		
		if(!isset($_POST['change'])){
			die("you have not select.Place try again<br/><a href=\"payReceived.php\">return</a>");
		}$change=$_POST['change'];?>
		<table BORDERCOLOR=black border="1">
			<tr>
					<td><h4>Payment ID</h4></td>
					<td><h4>Customer ID</h4></td>
					<td><h4>Customer NAME</h4></td>
					<td><h4>Customer Phone number</h4></td>
					<td><h4>Customer Address</h4></td>				
					<td><h4>Product ID</h4></td>
					<td><h4>Product Name</h4></td>
					<td><h4>No of Product</h4></td>
			</tr>
		<?php
		$size=count($change);
		for($i=0;$i<$size;$i++){
			$sql="update paymentlist set status = 'payment received' where payID = '".$change[$i]."'";
			$sql2="select pl.payID,pl.custid,
		c.custname,c.phoneNum,c.address,
		pl.proID,p.proName,pl.proNum
		from Paymentlist pl, product p ,customer c 
		where pl.proID=p.proID and c.custid=pl.custid and pl.payID='".$change[$i]."'";
			
			if($statement=oci_parse($conn,$sql)){
			oci_execute($statement);
			}
			
			else{die("update error<br/><a href=\"payReceived.php\">return</a>");}
			oci_free_statement($statement);
			
			if($statement=oci_parse($conn,$sql2)){
			oci_execute($statement);
			$row=oci_fetch_assoc($statement);
			echo"<tr>";
			echo"<td>".$row['PAYID']."</td>";					
			echo"<td>".$row['CUSTID']."</td>";										
			echo"<td>".$row['CUSTNAME']."</td>";
			echo"<td>".$row['PHONENUM']."</td>";
			echo"<td>".$row['ADDRESS']."</td>";
			echo"<td>".$row['PROID']."</td>";
			echo"<td>".$row['PRONAME']."</td>";
			echo"<td>".$row['PRONUM']."</td>";
			echo"</tr>";
			oci_free_statement($statement);
			}
			
			}
			
		
?>
			</table>
			</br><a href="adminPage.php">Return main page</a></br>
</body>
</html>