<html>
	<head><title>Admin page</title><head>
	<body>
		<?php
		session_start();
			require_once("oracle-functions.php");
			$conn = connectDB();	
			
		echo "<b>User name:".$_SESSION['custname']."</b>  | <a href=\"logout.php\">Logout</a>";
		echo"<hr />	
		<p><a href=\"newProduct.php\">Add new product</a>|<a href=\"paymentList.php\">Show the buyer list</a>
		|<a href=\"payReceived.php\">Payment received</a></p>
		<table BORDERCOLOR=black>";?>
		<a href="paymentList.php">Payment list</a>
	<form action="searchProduct.php" method="POST"> 
		<input type="text" name="keyWord" /> 
		<input type="radio" name="order" value="price" checked>Price
		<input type="radio" name="order" value="proID" checked>Product ID
		<input type="submit" value="Search" />
		<?php
			
				$sql="select * from Product order by proID";
				if($statement=oci_parse($conn,$sql)){
					oci_execute($statement);
					while($row=oci_fetch_assoc($statement)){	
			
			 echo"<tr>
				<td rowspan=\"5\"><IMG SRC='showPhoto.php?proID=".$row['PROID']."' width='200' height='150'/></td>";
			 
			 printf("<td><b>Product ID: </b></td><td>%s</td><td><b>Product Name: </b></td><td>%s</td></tr>",
			 htmlspecialchars($row['PROID']),htmlspecialchars($row['PRONAME']));
			
				
				
				printf("<tr><td><b>Product Type :</b></td><td>%s</td><td><b>Product for: </b></td><td>%s</td></tr>",htmlspecialchars($row['TOPIC']),htmlspecialchars($row['PET']));
				
				printf("<tr><td rowspan=\"2\" colspan=\"2\"><b>Product description: </b></td><td colspan=\"2\">%s</td></tr>", 
				htmlspecialchars($row['PRODESCRIPTION']));
				
				printf("<tr></tr><tr><td><b>Price(per one): </b></td><td colspan=\"3\">%.2f</td></tr><tr></tr>",floatval($row['PRICE'])); 
								
					}
					}
				oci_free_statement($statement);
				oci_close($conn);	
				?>			
		</table>
		
	</body>
</html>