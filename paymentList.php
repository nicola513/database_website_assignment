<html>
	<head><title>Payment list</title></head>
<body>
	<?php
	session_start();
			require_once("oracle-functions.php");
			$conn = connectDB();	
			
		echo "<b>User name:".$_SESSION['custname']."</b>  | <a href=\"logout.php\">Logout</a></br><hr />";
		
		if($_SESSION['custid']!='admin'){
			$sql="select pl.payID,pl.proID,p.proName,pl.proNum,pl.status from Paymentlist pl, product p where pl.custid='".$_SESSION['custid']."' and pl.proID=p.proID order by pl.payID";
			
			if($statement=oci_parse($conn,$sql)){
					oci_execute($statement);?>
			<table BORDERCOLOR=black style="border:6px black solid" rules="all">
				<tr>
					<td><h4>Payment ID</h4></td>
					<td><h4>Product ID</h4></td>
					<td><h4>Product Name</h4></td>
					<td><h4>No of Product</h4></td>
					<td><h4>Status</h4></td>
				</tr>
			<?php
					while($row=oci_fetch_assoc($statement)){	
					echo"<tr>";
					echo"<td>".$row['PAYID']."</td>";
					echo"<td>".$row['PROID']."</td>";
					echo"<td>".$row['PRONAME']."</td>";
					echo"<td>".$row['PRONUM']."</td>";
					echo"<td>".$row['STATUS']."</td>";
					echo"</tr>";
					}
					}
				oci_free_statement($statement);
				oci_close($conn);?>
			<br/>
			<a href="main.php">Return main page</a></br>				
		
		</table>
		<?php 
		}else{
		
		$sql="select pl.payID,pl.custid,c.custname,pl.proID,p.proName,pl.proNum,pl.status 
		from Paymentlist pl, product p ,customer c 
		where pl.proID=p.proID and c.custid=pl.custid";
		
		if($statement=oci_parse($conn,$sql)){
					oci_execute($statement);?>
			
			<table BORDERCOLOR=black border="1">
				<tr>
					<td><h4>Payment ID</h4></td>
					<td><h4>Customer ID</h4></td>
					<td><h4>Customer NAME</h4></td>
					<td><h4>Product ID</h4></td>
					<td><h4>Product Name</h4></td>
					<td><h4>No of Product</h4></td>
					<td><h4>Status</h4></td>
				</tr>
			<?php
					while($row=oci_fetch_assoc($statement)){	
					echo"<tr>";
					echo"<td>".$row['PAYID']."</td>";
					echo"<td>".$row['CUSTID']."</td>";
					echo"<td>".$row['CUSTNAME']."</td>";
					echo"<td>".$row['PROID']."</td>";
					echo"<td>".$row['PRONAME']."</td>";
					echo"<td>".$row['PRONUM']."</td>";
					echo"<td>".$row['STATUS']."</td>";
					echo"</tr>";
					}
					}
				oci_free_statement($statement);
				oci_close($conn);
				
			echo"<a href=\"adminPage.php\">Return main page</a></br></table>";
		}
	?>
	<br/>
	
</body>
</html>