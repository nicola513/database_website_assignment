<html>
	<head>
		<title>Customer main page</title>
	</head>
	<body>
	<?php
	session_start();
			require_once("oracle-functions.php");
			$conn = connectDB();	
			
		echo "<b>User name:".$_SESSION['custname']."</b>  | <a href=\"logout.php\">Logout</a></br><hr />";
	?>
	<a href="paymentList.php">Payment list</a>
	<form action="searchProduct.php" method="POST"> 
		<input type="text" name="keyWord" /> 
		<input type="radio" name="order" value="price" checked>Price
		<input type="radio" name="order" value="proID" checked>Product ID
		<input type="submit" value="Search" />
	</form>  
		<?php
		
		echo"<table BORDERCOLOR=black>";
		$sql="select * from Product order by proID";
				if($statement=oci_parse($conn,$sql)){
					oci_execute($statement);
					while($row=oci_fetch_assoc($statement)){	
		echo"<form action=\"buy.php?proID=".$row['PROID']."\" method=\"POST\">";	
			 echo"<tr>
				<td rowspan=\"5\"><IMG SRC='showPhoto.php?proID=".$row['PROID']."' width='200' height='150'/></td>";
			 
			 printf("<td><b>Product ID: </b></td><td>%s</td><td><b>Product Name: </b></td><td>%s</td></tr>",
			 htmlspecialchars($row['PROID']),htmlspecialchars($row['PRONAME']));
			
				
				
				printf("<tr><td><b>Product Type :</b></td><td>%s</td><td><b>Product for: </b></td><td>%s</td></tr>",htmlspecialchars($row['TOPIC']),htmlspecialchars($row['PET']));
				
				printf("<tr><td rowspan=\"2\" colspan=\"2\"><b>Product description: </b></td><td colspan=\"2\">%s</td></tr>", 
				htmlspecialchars($row['PRODESCRIPTION']));
				
				printf("<tr></tr><tr><td><b>Price(per one): </b></td><td colspan=\"2\">%.2f</td><td colspan=\"2\"><input type=\"submit\" value=\"Buy\" /></td></tr><tr></tr>",floatval($row['PRICE'])); 
				echo"</form>";
								
					}
					}
				oci_free_statement($statement);
				oci_close($conn);	
		?>
		</table>
		
	</body>
</html>