<html>
	<head><title>Payment Received</title></head>
	<body>
		<?php
		session_start();
		require_once("oracle-functions.php");
		$conn = connectDB();	
			
		echo "<b>User name:".$_SESSION['custname']."</b>  | <a href=\"logout.php\">Logout</a></br><hr />";
		?>
		<form action="statusChange.php" method="POST">
			<table BORDERCOLOR=black border="1">
				<tr>
					<td><h4>Payment ID</h4></td>
					<td><h4>Customer ID</h4></td>
					<td><h4>Customer NAME</h4></td>			
					<td><h4>Product ID</h4></td>
					<td><h4>Product Name</h4></td>
					<td><h4>No of Product</h4></td>
					<td><h4>Status</h4></td>
					<td><h4>Change status</h4></td>
				</tr>
				<?php
		$sql="select pl.payID,pl.custid,
		c.custname,pl.proID,p.proName,pl.proNum,pl.status 
		from Paymentlist pl, product p ,customer c 
		where pl.proID=p.proID and c.custid=pl.custid and pl.status='waiting for payment'";
					
		if($statement=oci_parse($conn,$sql)){
					oci_execute($statement);
					while($row=oci_fetch_assoc($statement)){	
					echo"<tr>";
					echo"<td>".$row['PAYID']."</td>";					
					echo"<td>".$row['CUSTID']."</td>";										
					echo"<td>".$row['CUSTNAME']."</td>";
					echo"<td>".$row['PROID']."</td>";
					echo"<td>".$row['PRONAME']."</td>";
					echo"<td>".$row['PRONUM']."</td>";
					echo"<td>".$row['STATUS']."</td>";
					echo"<td><input type=\"checkbox\" name=\"change[]\" value=\"".$row['PAYID']."\"></td>";
					echo"</tr>";
					}
					echo"<tr><input type=\"submit\" value=\"Upload\"></tr>";
					}
				oci_free_statement($statement);
				oci_close($conn);
	?>
			</table>
		</form>
		</br><a href="adminPage.php">Return main page</a></br>
	</body>
</html>